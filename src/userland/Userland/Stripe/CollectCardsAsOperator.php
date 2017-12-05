<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;

/**
 * @di\service()
 */
class CollectCardsAsOperator
{
    /**
     * @var Userland\Stripe\CollectCards
     */
    private $collectCards;

    /**
     * @param Userland\Stripe\CollectCards $collectCards
     */
    public function __construct(
        Userland\Stripe\CollectCards $collectCards
    ) {
        $this->collectCards = $collectCards;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/stripe/collect-cards-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $user
     *
     * @return Cards
     */
    public function collect(
        string $user
    ) {
        $cards = new Cards(
            $this->collectCards->collect($user)->getIterator()
        );

        return $cards;
    }
}