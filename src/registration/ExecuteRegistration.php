<?php

namespace Yosmy\Userland;

use Yosmy\Userland;

/**
 * @di\service()
 */
class ExecuteRegistration
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var Userland\Uniqueness\AddUser
     */
    private $addUniquenessUser;

    /**
     * @var Registration\AddUser
     */
    private $addRegistrationUser;

    /**
     * @var Authentication\AddUser
     */
    private $addAuthenticationUser;

    /**
     * @var Privilege\AddUser
     */
    private $addPrivilegeUser;

    /**
     * @var Userland\Phone\AddUser
     */
    private $addPhoneUser;

    /**
     * @var Push\AddUser
     */
    private $addPushUser;

    /**
     * @param Phone\PickUser $pickPhoneUser
     * @param Uniqueness\AddUser $addUniquenessUser
     * @param Registration\AddUser $addRegistrationUser
     * @param Authentication\AddUser $addAuthenticationUser
     * @param Privilege\AddUser $addPrivilegeUser
     * @param Phone\AddUser $addPhoneUser
     * @param Push\AddUser $addPushUser
     */
    public function __construct(
        Phone\PickUser $pickPhoneUser,
        Uniqueness\AddUser $addUniquenessUser,
        Registration\AddUser $addRegistrationUser,
        Authentication\AddUser $addAuthenticationUser,
        Privilege\AddUser $addPrivilegeUser,
        Phone\AddUser $addPhoneUser,
        Push\AddUser $addPushUser
    ) {
        $this->pickPhoneUser = $pickPhoneUser;
        $this->addUniquenessUser = $addUniquenessUser;
        $this->addRegistrationUser = $addRegistrationUser;
        $this->addAuthenticationUser = $addAuthenticationUser;
        $this->addPrivilegeUser = $addPrivilegeUser;
        $this->addPhoneUser = $addPhoneUser;
        $this->addPushUser = $addPushUser;
    }

    /**
     * @param string   $country
     * @param string   $prefix
     * @param string   $number
     * @param string[] $roles
     *
     * @return string The user id
     */
    public function execute(
        $country,
        $prefix,
        $number,
        $roles
    ) {
        try {
            $phoneUser = $this->pickPhoneUser->pick(null, $country, $prefix, $number);

            $id = $phoneUser->getId();
        } catch (Userland\Phone\NonexistentUserException $e) {
            try {
                $id = $this->addUniquenessUser->add();
            } catch (Userland\Uniqueness\ExistentUserException $e) {
                throw new \LogicException(null, null, $e);
            }

            try {
                $this->addRegistrationUser->add(
                    $id
                );
            } catch (Registration\ExistentUserException $e) {
                throw new \LogicException(null, null, $e);
            }

            try {
                $this->addAuthenticationUser->add(
                    $id
                );
            } catch (Authentication\ExistentUserException $e) {
                throw new \LogicException(null, null, $e);
            }

            try {
                $this->addPrivilegeUser->add(
                    $id,
                    $roles
                );
            } catch (Privilege\ExistentUserException $e) {
                throw new \LogicException(null, null, $e);
            }

            try {
                $this->addPhoneUser->add(
                    $id,
                    $country,
                    $prefix,
                    $number
                );
            } catch (Userland\Phone\ExistentUserException $e) {
                throw new \LogicException(null, null, $e);
            }

            try {
                $this->addPushUser->add(
                    $id,
                    ''
                );
            } catch (Push\ExistentUserException $e) {
                throw new \LogicException(null, null, $e);
            }
        }

        return $id;
    }
}
