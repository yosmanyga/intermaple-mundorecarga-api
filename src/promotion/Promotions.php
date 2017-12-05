<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

class Promotions extends Recharge\Ding\Countries
{
    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        parent::__construct(
            $cursor,
            Promotion::class
        );
    }
}
