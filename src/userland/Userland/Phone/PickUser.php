<?php

namespace Intermaple\Mundorecarga\Userland\Phone;

use Yosmy\Userland;

/**
 * @di\service()
 */
class PickUser
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
     * @http\resolution({method: "POST", path: "/userland/phone/pick-user"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id
     *
     * @return User
     */
    public function pick(
        string $id
    ) {
        try {
            $user = $this->pickUser->pick($id, null, null, null);
        } catch (Userland\Phone\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        return new User($user);
    }
}