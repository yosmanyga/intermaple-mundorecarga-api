<?php

namespace Intermaple\Mundorecarga\Userland\Phone;

use Yosmy\Userland;

/**
 * @di\service()
 */
class PickUserAsClient
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickUser;

    /**
     * @param Userland\Phone\PickUser $pickUser
     */
    public function __construct(
        Userland\Phone\PickUser $pickUser
    ) {
        $this->pickUser = $pickUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/phone/pick-user-as-client"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     *
     * @return User
     */
    public function pick(
        string $client
    ) {
        try {
            $user = $this->pickUser->pick($client, null, null, null);
        } catch (Userland\Phone\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        return new User($user);
    }
}