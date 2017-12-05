<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

class Countries extends Recharge\Ding\Countries
{
    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        parent::__construct(
            $cursor,
            Country::class
        );
    }
}
