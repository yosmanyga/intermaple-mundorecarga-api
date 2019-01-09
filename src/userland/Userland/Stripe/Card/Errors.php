<?php

namespace Intermaple\Mundorecarga\Userland\Stripe\Card;

use Yosmy\Userland;

class Errors extends Userland\Stripe\Cards
{
    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        parent::__construct(
            $cursor,
            Error::class
        );
    }
}
