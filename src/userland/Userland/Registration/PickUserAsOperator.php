<?php

namespace Intermaple\Mundorecarga\Userland\Registration;

use Yosmy\Userland;

/**
 * @di\service()
 */
class PickUserAsOperator
{
    /**
     * @var Userland\Registration\PickUser
     */
    private $pickUser;

    /**
     * @param Userland\Registration\PickUser $pickUser
     */
    public function __construct(
        Userland\Registration\PickUser $pickUser
    ) {
        $this->pickUser = $pickUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/registration/pick-user-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id
     *
     * @return User
     */
    public function pick(
        string $id
    ) {
        $user = $this->pickUser->pick($id);

        return new User($user);
    }
}