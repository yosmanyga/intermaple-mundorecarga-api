<?php

namespace Yosmy\Userland;

use Yosmy\Userland;

/**
 * @di\service()
 */
class AddRole
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var Privilege\User\AddRole
     */
    private $addRole;

    /**
     * @param Userland\Phone\PickUser $pickPhoneUser
     * @param Privilege\User\AddRole $addRole
     */
    public function __construct(
        Userland\Phone\PickUser $pickPhoneUser,
        Privilege\User\AddRole $addRole
    ) {
        $this->pickPhoneUser = $pickPhoneUser;
        $this->addRole = $addRole;
    }

    /**
     * @cli\resolution({command: "/add-role"})
     *
     * @param string $country
     * @param string $prefix
     * @param string $number
     * @param string $role
     */
    public function add($country, $prefix, $number, $role)
    {
        try {
            $privilegeUser = $this->pickPhoneUser->pick(null, $country, $prefix, $number);
        } catch (Userland\Phone\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        $this->addRole->add(
            $privilegeUser->getId(),
            $role
        );
    }
}
