<?php

namespace Intermaple\Mundorecarga;

use MongoDB\BSON\UTCDateTime;
use Yosmy\Userland;
use Yosmy\Recharge;
use Intermaple\Mundorecarga\Blacklist;

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
     * @var Userland\SendEmail
     */
    private $sendEmail;

    /**
     * @var PickCountry
     */
    private $pickCountry;

    /**
     * @var PickProduct
     */
    private $pickProduct;

    /**
     * @var PickProvider
     */
    private $pickProvider;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;
    
    /**
     * @var PickTopup
     */
    private $pickTopup;

    /**
     * @var Topup\DetectHighUsage
     */
    private $detectHighUsage;

    /**
     * @var Userland\Stripe\CalculateFee
     */
    private $calculateFee;

    /**
     * @var Userland\Stripe\ChargeCard
     */
    private $chargeCard;

    /**
     * @var ResolveContact
     */
    private $resolveContact;

    /**
     * @var PickContact
     */
    private $pickContact;

    /**
     * @var Blacklist\PickContact
     */
    private $pickBlacklistContact;

    /**
     * @var Recharge\Ding\SendTransfer
     */
    private $sendTransfer;

    /**
     * @var callable[]
     */
    private $listeners;

    /**
     * @di\arguments({
     *     env: "%env%",
     *     listeners: '#topup_sent'
     * })
     *
     * @param string $env
     * @param Userland\SendEmail $sendEmail
     * @param PickCountry $pickCountry
     * @param PickProduct $pickProduct
     * @param PickProvider $pickProvider
     * @param SelectTopupCollection $selectTopupCollection
     * @param PickTopup $pickTopup
     * @param Topup\DetectHighUsage $detectHighUsage
     * @param Userland\Stripe\CalculateFee $calculateFee
     * @param Userland\Stripe\ChargeCard $chargeCard
     * @param ResolveContact $resolveContact
     * @param PickContact $pickContact
     * @param Blacklist\PickContact $pickBlacklistContact;
     * @param Recharge\Ding\SendTransfer $sendTransfer
     * @param callable[]  $listeners
     */
    public function __construct(
        string $env,
        Userland\SendEmail $sendEmail,
        PickCountry $pickCountry,
        PickProduct $pickProduct,
        PickProvider $pickProvider,
        SelectTopupCollection $selectTopupCollection,
        PickTopup $pickTopup,
        Topup\DetectHighUsage $detectHighUsage,
        Userland\Stripe\CalculateFee $calculateFee,
        Userland\Stripe\ChargeCard $chargeCard,
        ResolveContact $resolveContact,
        PickContact $pickContact,
        Blacklist\PickContact $pickBlacklistContact,
        Recharge\Ding\SendTransfer $sendTransfer,
        array $listeners
    ) {
        $this->env = $env;
        $this->sendEmail = $sendEmail;
        $this->pickCountry = $pickCountry;
        $this->pickProduct = $pickProduct;
        $this->pickProvider = $pickProvider;
        $this->selectTopupCollection = $selectTopupCollection;
        $this->pickTopup = $pickTopup;
        $this->detectHighUsage = $detectHighUsage;
        $this->calculateFee = $calculateFee;
        $this->chargeCard = $chargeCard;
        $this->resolveContact = $resolveContact;
        $this->pickContact = $pickContact;
        $this->pickBlacklistContact = $pickBlacklistContact;
        $this->sendTransfer = $sendTransfer;
        $this->listeners = $listeners;
    }

    /**
     * @http\resolution({method: "POST", path: "/send-topup"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $country
     * @param string $account
     * @param string $type
     * @param string $product
     * @param float  $amount
     * @param string $card
     *
     * @return string The contact id
     *
     * @throws Topup\ContactException
     * @throws Topup\PaymentException
     */
    public function send(
        $client,
        $country,
        $account,
        $type,
        $product,
        $amount,
        $card
    ) {
        if ($this->env != 'dev') {
            $this->sendEmail->send(
                'yosmanyga@gmail.com',
                'Nueva recarga',
                print_r([
                    'client' => $client,
                    'country' => $country,
                    'account' => $account,
                    'product' => $product,
                    'amount' => $amount,
                    'card' => $card,
                ], true)
            );
        }

        try {
            $country = $this->pickCountry->pick($country);
        } catch (NonexistentCountryException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $product = $this->pickProduct->pick($product);
        } catch (NonexistentProductException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $provider = $this->pickProvider->pick($product->getProvider());
        } catch (NonexistentProviderException $e) {
            throw new \LogicException(null, null, $e);
        }

        $contact = $this->resolveContact->resolve(
            $client,
            $country,
            $account,
            $type,
            $provider
        );

        try {
            // Is banned?
            $this->pickBlacklistContact->pick($contact->getPrefix(), $contact->getAccount());

            throw new Topup\ContactException("La recarga no ha sido autorizada");
        } catch (Blacklist\NonexistentContactException $e) {
        }

        $id = uniqid();

        $this->selectTopupCollection->select()->insertOne([
            '_id' => $id,
            'contact' => $contact->getId(),
            'stripe' => null,
            'charge' => null,
            'fee' => null,
            'ding' => null,
            'product' => $product->getId(),
            'amount' => $amount,
            'attempts' => 0,
            'profit' => null,
            'date' => new UTCDateTime(time() * 1000),
            'steps' => []
        ]);

        /* Payment */

        try {
            // Plus Stripe fee
            $fee = $this->calculateFee->calculate($amount);
            $charge = $amount + $fee;

            $stripe = $this->chargeCard->charge(
                $client,
                $card,
                $charge,
                $type === "phone"
                    ? sprintf(
                        '$%s al +%s-%s',
                        $amount,
                        $country->getPrefix(),
                        $account
                    )
                    : sprintf(
                    '$%s al %s',
                        $amount,
                        $account
                    ),
                sprintf(
                    'Recarga %s',
                    $contact->getName() != ""
                        ? sprintf('%s (%s)', $account, $contact->getName())
                        : $account
                )
            );

            $this->selectTopupCollection->select()->updateOne(
                ['_id' => $id],
                [
                    '$set' => [
                        'stripe' => $stripe,
                        'charge' => $charge,
                        'fee' => $fee,
                    ],
                    '$push' => ['steps' => Topup::STEP_PAYMENT]
                ]
            );
        } catch (Userland\Stripe\Card\Exception $e) {
            $this->selectTopupCollection->select()->deleteOne(
                ['_id' => $id]
            );

            throw new Topup\PaymentException($e->getMessage());
        } catch (\LogicException $e) {
            $this->selectTopupCollection->select()->deleteOne(
                ['_id' => $id]
            );

            throw new \LogicException($e->getMessage());
        }

        /* Fraud */

        if ($this->detectHighUsage->detect($client, $amount)) {
            return $contact->getId();
        }

        /* Transfer */

        $this->sendTransfer(
            $id,
            $contact,
            $product->getId(),
            $amount
        );

        return $contact->getId();
    }

    /**
     * @http\resolution({method: "POST", path: "/send-delayed-topup"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id Topup id
     *
     * @return Topup
     */
    public function sendDelayed($id)
    {
        $topup = $this->pickTopup->pick($id);

        if (
            $topup->getSteps() != [Topup::STEP_PAYMENT]
        ) {
            throw new \LogicException();
        }

        $contact = $this->pickContact->pick($topup->getContact(), null);

        try {
            $this->sendTransfer(
                $id,
                $contact,
                $topup->getProduct(),
                $topup->getAmount()
            );
        } catch (\Exception $e) {
            // Ignore exception
        }

        /* Return topup with updated attempts and step fields */

        /** @var Topup $topup */
        $topup = $this->selectTopupCollection
            ->select()
            ->findOne(
                ['_id' => $id]
            );

        return $topup;
    }

    /**
     * @http\resolution({method: "POST", path: "/try-sending-topup-again"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id Topup id
     *
     * @return Topup
     */
    public function trySendingAgain($id)
    {
        $topup = $this->pickTopup->pick($id);

        if (
            // Did not have problems?
            !in_array(Topup::STEP_TRANSFER_EXCEPTION, $topup->getSteps())
            // Already sent?
            || in_array(Topup::STEP_TRANSFER_SUCCESS, $topup->getSteps())
        ) {
            throw new \LogicException();
        }

        $contact = $this->pickContact->pick($topup->getContact(), null);

        try {
            $this->sendTransfer(
                $id,
                $contact,
                $topup->getProduct(),
                $topup->getAmount()
            );
        } catch (\Exception $e) {
            // Ignore exception
        }

        /* Return topup with updated attempts and step fields */

        /** @var Topup $topup */
        $topup = $this->selectTopupCollection
            ->select()
            ->findOne(
                ['_id' => $id]
            );

        return $topup;
    }

    /**
     * @param string  $id
     * @param Contact $contact
     * @param string  $product
     * @param float   $amount
     */
    private function sendTransfer(
        $id,
        Contact $contact,
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
                $contact->getPrefix(),
                $contact->getAccount(),
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

            foreach ($this->listeners as $listener) {
                $listener(
                    $id
                );
            }
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
