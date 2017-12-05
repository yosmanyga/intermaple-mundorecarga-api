<?php

namespace Intermaple\Mundorecarga\Userland\Privilege;

use Yosmy\Userland;

/**
 * @di\service()
 */
class PickUser
{
    /**
     * @var Userland\Privilege\PickUser
     */
    private $pickUser;

    /**
     * @param Userland\Privilege\PickUser $pickUser
     */
    public function __construct(Userland\Privilege\PickUser $pickUser)
    {
        $this->pickUser = $pickUser;
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function pick(
        string $id
    ) {
        try {
            $user = $this->pickUser->pick($id);
        } catch (Userland\Privilege\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        return new User($user);
    }
}