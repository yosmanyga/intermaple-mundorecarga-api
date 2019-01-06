<?php

namespace Intermaple\Mundorecarga\Reseller;

use Intermaple\Mundorecarga;
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
        Recharge\Ding\SendTransfer $sendTransfer
    ) {
        $this->env = $env;
        $this->pickAgent = $pickAgent;
        $this->pickCountry = $pickCountry;
        $this->pickProduct = $pickProduct;
        $this->pickProvider = $pickProvider;
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

        $id = uniqid();

        $this->selectTopupCollection->select()->insertOne([
            '_id' => $id,
            'agent' => $agent->getId(),
            'ding' => null,
            'product' => $product->getId(),
            'amount' => $amount,
            'attempts' => 0,
            'profit' => null,
            'date' => new UTCDateTime(time() * 1000),
            'steps' => []
        ]);

        /* Transfer */

        $this->sendTransfer(
            $id,
            $country->getPrefix(),
            $account,
            $amount,
            $product->getId()
        );
    }

    /**
     * @param string  $id
     * @param string  $prefix
     * @param string  $account
     * @param int     $amount
     * @param string  $product
     */
    private function sendTransfer(
        $id,
        $prefix,
        $account,
        $amount,
        string $product
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
