<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

use Yosmy\Userland;

/**
 * @di\service()
 */
class PickUserAsOperator
{
    /**
     * @var Userland\Stripe\PickUser
     */
    private $pickUser;

    /**
     * @param Userland\Stripe\PickUser $pickUser
     */
    public function __construct(
        Userland\Stripe\PickUser $pickUser
    ) {
        $this->pickUser = $pickUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/stripe/pick-user-as-operator"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $user
     *
     * @return User
     *
     * @throws NonexistentUserException
     */
    public function pick(
        string $user
    ) {
        try {
            $user = $this->pickUser->pick($user);
        } catch (Userland\Stripe\NonexistentUserException $e) {
            throw new NonexistentUserException();
        }

        return new User($user);
    }
}