<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;

/**
 * @di\service()
 */
class DeleteCard
{
    /**
     * @var Userland\Stripe\DeleteCard
     */
    private $deleteCard;

    /**
     * @param Userland\Stripe\DeleteCard $deleteCard
     */
    public function __construct(
        Userland\Stripe\DeleteCard $deleteCard
    ) {
        $this->deleteCard = $deleteCard;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/stripe/delete-card"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $id
     */
    public function delete(
        string $client,
        string $id
    ) {
        $this->deleteCard->delete(
            $id,
            $client
        );
    }
}