<?php

namespace Intermaple\Mundorecarga\Reseller;

use Intermaple\Mundorecarga;
use Intermaple\Mundorecarga\Reseller;
use MongoDB\BSON\UTCDateTime;
use Yosmy\Recharge;

/**
 * @di\service()
 */
class SendTopup
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var PickAgent
     */
    private $pickAgent;

    /**
     * @var Mundorecarga\PickCountry
     */
    private $pickCountry;

    /**
     * @var Mundorecarga\PickProduct
     */
    private $pickProduct;

    /**
     * @var Mundorecarga\PickProvider
     */
    private $pickProvider;

    /**
     * @var Reseller\User\DecreaseBalance
     */
    private $decreaseBalance;

    /**
     * @var Reseller\User\IncreaseBalance
     */
    private $increaseBalance;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;
    
    /**
     * @var Recharge\Ding\SendTransfer
     */
    private $sendTransfer;

    /**
     * @di\arguments({
     *     env: "%env%"
     * })
     *
     * @param string $env
     * @param PickAgent $pickAgent
     * @param Mundorecarga\PickCountry $pickCountry
     * @param Mundorecarga\PickProduct $pickProduct
     * @param Mundorecarga\PickProvider $pickProvider
     * @param Reseller\User\DecreaseBalance $decreaseBalance
     * @param Reseller\User\IncreaseBalance $increaseBalance
     * @param SelectTopupCollection $selectTopupCollection
     * @param Recharge\Ding\SendTransfer $sendTransfer
     */
    public function __construct(
        string $env,
        PickAgent $pickAgent,
        Mundorecarga\PickCountry $pickCountry,
        Mundorecarga\PickProduct $pickProduct,
        Mundorecarga\PickProvider $pickProvider,
        SelectTopupCollection $selectTopupCollection,
        Reseller\User\DecreaseBalance $decreaseBalance,
        Reseller\User\IncreaseBalance $increaseBalance,
        Recharge\Ding\SendTransfer $sendTransfer
    ) {
        $this->env = $env;
        $this->pickAgent = $pickAgent;
        $this->pickCountry = $pickCountry;
        $this->pickProduct = $pickProduct;
        $this->pickProvider = $pickProvider;
        $this->decreaseBalance = $decreaseBalance;
        $this->increaseBalance = $increaseBalance;
        $this->selectTopupCollection = $selectTopupCollection;
        $this->sendTransfer = $sendTransfer;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/send-topup"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     * @param string $agent
     * @param string $country
     * @param string $account
     * @param string $product
     * @param float  $amount
     *
     * @throws PaymentException
     */
    public function send(
        $reseller,
        $agent,
        $country,
        $account,
        $product,
        $amount
    ) {
        $agent = $this->pickAgent->pick(
            $agent,
            $reseller
        );

        try {
            $country = $this->pickCountry->pick($country);
        } catch (Mundorecarga\NonexistentCountryException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $product = $this->pickProduct->pick($product);
        } catch (Mundorecarga\NonexistentProductException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $this->pickProvider->pick($product->getProvider());
        } catch (Mundorecarga\NonexistentProviderException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $this->decreaseBalance->decrease(
                $reseller,
                $amount
            );
        } catch (User\InsufficientBalanceException $e) {
            throw new PaymentException();
        }

        $id = uniqid();

        $this->selectTopupCollection->select()->insertOne([
            '_id' => $id,
            'agent' => $agent->getId(),
            'account' => $account,
            'product' => $product->getId(),
            'amount' => $amount,
            'ding' => null,
            'attempts' => 0,
            'profit' => null,
            'date' => new UTCDateTime(time() * 1000),
            'steps' => []
        ]);

        /* Transfer */

        try {
            $this->sendTransfer(
                $id,
                $country->getPrefix(),
                $account,
                $product->getId(),
                $amount
            );
        } catch (\Exception $e) {
            $this->increaseBalance->increase(
                $reseller,
                $amount
            );
        }
    }

    /**
     * @param string  $id
     * @param string  $prefix
     * @param string  $account
     * @param string  $product
     * @param float   $amount
     */
    private function sendTransfer(
        $id,
        $prefix,
        $account,
        string $product,
        float $amount
    ) {
        if ($this->env == 'dev') {
            return;
        }

        $this->selectTopupCollection->select()->updateOne(
            ['_id' => $id],
            ['$inc' => [
                'attempts' => 1
            ]]
        );

        try {
            $receipt = $this->sendTransfer->send(
                $id,
                $prefix,
                $account,
                $product,
                $amount
            );

            $this->selectTopupCollection->select()->updateOne(
                ['_id' => $id],
                [
                    '$set' => [
                        'ding' => $receipt->getId(),
                        'profit' => $receipt->getCommission(),
                    ],
                    '$push' => ['steps' => Topup::STEP_TRANSFER_SUCCESS]
                ]
            );
        } catch (\Exception $e) {
            $this->selectTopupCollection->select()->updateOne(
                ['_id' => $id],
                [
                    '$push' => ['steps' => Topup::STEP_TRANSFER_EXCEPTION]
                ]
            );

            throw new \LogicException(null, null, $e);
        }
    }
}
