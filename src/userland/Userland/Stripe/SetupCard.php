<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;
use Yosmy\Userland\Blacklist;
use Yosmy\Userland\Phone;

/**
 * @di\service()
 */
class SetupCard
{
    /**
     * @var Blacklist\PickUser
     */
    private $pickBlacklistUser;

    /**
     * @var Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var Userland\Stripe\SetupCard
     */
    private $setupCard;

    /**
     * @param Blacklist\PickUser $pickBlacklistUser
     * @param Phone\PickUser $pickPhoneUser
     * @param Userland\Stripe\SetupCard $setupCard
     */
    public function __construct(
        Blacklist\PickUser $pickBlacklistUser,
        Phone\PickUser $pickPhoneUser,
        Userland\Stripe\SetupCard $setupCard
    ) {
        $this->pickBlacklistUser = $pickBlacklistUser;
        $this->pickPhoneUser = $pickPhoneUser;
        $this->setupCard = $setupCard;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/stripe/setup-card"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $number
     * @param string $name
     * @param string $month
     * @param string $year
     * @param string $cvc
     * @param string $zip
     * @param bool   $save
     *
     * @return Card
     *
     * @throws Exception
     */
    public function setup(
        string $client,
        string $number,
        string $name,
        string $month,
        string $year,
        string $cvc,
        string $zip,
        bool $save
    ) {
        try {
            $this->pickBlacklistUser->pick($client);

            throw new Exception("Tu cuenta ha sido bloqueada por uso indebido");
        } catch (Blacklist\NonexistentUserException $e) {
        }

        try {
            $user = $this->pickPhoneUser->pick($client, null, null, null);
        } catch (Phone\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $card = $this->setupCard->setup(
                $client,
                $user->getCountry(),
                $number,
                $name,
                $month,
                $year,
                $cvc,
                $zip,
                $save
            );

            return new Card($card);
        } catch (Userland\Stripe\Card\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}