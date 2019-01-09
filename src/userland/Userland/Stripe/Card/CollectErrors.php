<?php

namespace Intermaple\Mundorecarga\Userland\Stripe\Card;

use Yosmy\Userland;

/**
 * @di\service()
 */
class CollectErrors
{
    /**
     * @var Userland\Stripe\Card\CollectErrors
     */
    private $collectErrors;

    /**
     * @param Userland\Stripe\Card\CollectErrors $collectErrors
     */
    public function __construct(
        Userland\Stripe\Card\CollectErrors $collectErrors
    ) {
        $this->collectErrors = $collectErrors;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/stripe/card/collect-errors"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $user
     * @param int    $from
     * @param int    $to
     *
     * @return Errors
     */
    public function collect(
        ?string $user,
        ?int $from,
        ?int $to
    ) {
        $errors = new Errors(
            $this->collectErrors->collect($user, $from, $to)->getIterator()
        );

        return $errors;
    }
}