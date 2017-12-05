<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;

/**
 * @di\service()
 */
class CollectCardsAsClient
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
     * @http\resolution({method: "POST", path: "/userland/stripe/collect-cards-as-client"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     *
     * @return Cards
     */
    public function collect($client)
    {
        $cards = new Cards(
            $this->collectCards->collect($client)->getIterator()
        );

        return $cards;
    }
}