<?php

namespace Intermaple\Mundorecarga\Userland\Privilege;

use Yosmy\Userland;

class Users extends Userland\Privilege\Users
{
    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        parent::__construct(
            $cursor,
            User::class
        );
    }
}
