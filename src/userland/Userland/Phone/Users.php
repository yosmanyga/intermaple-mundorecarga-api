<?php

namespace Intermaple\Mundorecarga\Userland\Phone;

use Yosmy\Userland;

class Users extends Userland\Phone\Users
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
