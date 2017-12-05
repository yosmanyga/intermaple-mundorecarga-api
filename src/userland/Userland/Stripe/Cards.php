<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;

class Cards extends Userland\Stripe\Cards
{
    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        parent::__construct(
            $cursor,
            Card::class
        );
    }
}
