<?php

namespace Intermaple\Mundorecarga\Userland;

use Intermaple\Mundorecarga\Reseller;
use Yosmy\Userland;

/**
 * @di\service()
 */
class CreateReseller
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var Userland\ExecuteRegistration
     */
    private $executeRegistration;

    /**
     * @var Userland\Password\AddUser
     */
    private $addPasswordUser;

    /**
     * @var Reseller\AddUser
     */
    private $addResellerUser;

    /**
     * @var Reseller\AddAgent
     */
    private $addAgent;

    /**
     * @param Userland\Phone\PickUser      $pickPhoneUser
     * @param Userland\ExecuteRegistration $executeRegistration
     * @param Userland\Password\AddUser    $addPasswordUser
     * @param Reseller\AddUser             $addResellerUser
     * @param Reseller\AddAgent            $addAgent
     */
    public function __construct(
        Userland\Phone\PickUser $pickPhoneUser,
        Userland\ExecuteRegistration $executeRegistration,
        Userland\Password\AddUser $addPasswordUser,
        Reseller\AddUser $addResellerUser,
        Reseller\AddAgent $addAgent
    ) {
        $this->pickPhoneUser = $pickPhoneUser;
        $this->executeRegistration = $executeRegistration;
        $this->addPasswordUser = $addPasswordUser;
        $this->addResellerUser = $addResellerUser;
        $this->addAgent = $addAgent;
    }

    /**
     * @cli\resolution({command: "/userland/create-reseller"})
     *
     * @param string $country
     * @param string $prefix
     * @param string $number
     * @param string $name
     * @param string $password
     * @param float  $balance
     */
    public function create(
        string $country,
        string $prefix,
        string $number,
        string $name,
        string $password,
        float $balance
    ) {
        /* Verify user existence */

        try {
            $this->pickPhoneUser->pick(null, $country, $prefix, $number)->getId();

            return null;
        } catch (Userland\Phone\NonexistentUserException $e) {
        }

        $id = $this->executeRegistration->execute(
            $country,
            $prefix,
            $number,
            ['reseller']
        );

        try {
            $this->addPasswordUser->add(
                $id,
                $password
            );
        } catch (Userland\Password\ExistentUserException $e) {
            throw new \LogicException();
        }

        $this->addResellerUser->add(
            $id,
            $name,
            $balance
        );

        $this->addAgent->add($id, $name);
    }
}