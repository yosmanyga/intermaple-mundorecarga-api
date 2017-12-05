<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;

/**
 * @di\service()
 */
class SetupCard
{
    /**
     * @var Userland\Stripe\SetupCard
     */
    private $setupCard;

    /**
     * @param Userland\Stripe\SetupCard $setupCard
     */
    public function __construct(
        Userland\Stripe\SetupCard $setupCard
    ) {
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
            $card = $this->setupCard->setup(
                $client,
                $number,
                $name,
                $month,
                $year,
                $cvc,
                $zip,
                $save
            );

            return new Card($card);
        } catch (Userland\Stripe\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}