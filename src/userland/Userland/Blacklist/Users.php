<?php

namespace Intermaple\Mundorecarga\Userland\Blacklist;

use Yosmy\Userland;

class Users extends Userland\Blacklist\Users
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
