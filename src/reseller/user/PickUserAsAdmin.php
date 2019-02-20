<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class PickUserAsAdmin
{
    /**
     * @var PickUser
     */
    private $pickUser;

    /**
     * @param PickUser $pickUser
     */
    public function __construct(
        PickUser $pickUser
    ) {
        $this->pickUser = $pickUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/pick-user-as-admin"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $user
     *
     * @return User
     */
    public function pick(
        string $user
    ) {
        return $this->pickUser->pick($user);
    }
}