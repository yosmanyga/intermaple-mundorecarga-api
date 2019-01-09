<?php

namespace Intermaple\Mundorecarga\Userland\Blacklist;

use Yosmy\Userland;

/**
 * @di\service()
 */
class PickUserAsOperator
{
    /**
     * @var Userland\Blacklist\PickUser
     */
    private $pickUser;

    /**
     * @param Userland\Blacklist\PickUser $pickUser
     */
    public function __construct(
        Userland\Blacklist\PickUser $pickUser
    ) {
        $this->pickUser = $pickUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/blacklist/pick-user-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id
     *
     * @return User
     *
     * @throws NonexistentUserException
     */
    public function pick(
        string $id
    ) {
        try {
            $user = $this->pickUser->pick($id);
        } catch (Userland\Blacklist\NonexistentUserException $e) {
            throw new NonexistentUserException();
        }

        return new User($user);
    }
}