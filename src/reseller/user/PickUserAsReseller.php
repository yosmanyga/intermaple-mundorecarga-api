<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class PickUserAsReseller
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
     * @http\resolution({method: "POST", path: "/reseller/pick-user-as-reseller"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     *
     * @return User
     */
    public function pick(
        string $reseller
    ) {
        return $this->pickUser->pick($reseller);
    }
}