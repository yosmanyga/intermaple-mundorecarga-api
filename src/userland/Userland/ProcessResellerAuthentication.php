<?php

namespace Intermaple\Mundorecarga\Userland;

use Yosmy\Userland;
use Lcobucci\JWT;

/**
 * @di\service()
 */
class ProcessResellerAuthentication
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var Userland\Privilege\PickUser
     */
    private $pickPrivilegeUser;

    /**
     * @var Userland\Password\PickUser
     */
    private $pickPasswordUser;

    /**
     * @var Userland\Password\User\ValidatePassword
     */
    private $validatePassword;

    /**
     * @var string
     */
    private $jwtSecret;

    /**
     * @di\arguments({
     *     jwtSecret: "%jwt_secret%"
     * })
     *
     * @param Userland\Phone\PickUser $pickPhoneUser
     * @param Userland\Privilege\PickUser $pickPrivilegeUser
     * @param Userland\Password\PickUser $pickPasswordUser
     * @param Userland\Password\User\ValidatePassword $validatePassword
     * @param string $jwtSecret
     */
    public function __construct(
        Userland\Phone\PickUser $pickPhoneUser,
        Userland\Privilege\PickUser $pickPrivilegeUser,
        Userland\Password\PickUser $pickPasswordUser,
        Userland\Password\User\ValidatePassword $validatePassword,
        string $jwtSecret
    ) {
        $this->pickPhoneUser = $pickPhoneUser;
        $this->pickPrivilegeUser = $pickPrivilegeUser;
        $this->pickPasswordUser = $pickPasswordUser;
        $this->validatePassword = $validatePassword;
        $this->jwtSecret = $jwtSecret;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/process-reseller-authentication"})
     *
     * @param string $country
     * @param string $prefix
     * @param string $number
     * @param string $password
     *
     * @return ResellerAuthentication
     *
     * @throws InvalidAuthenticationException
     */
    public function process(
        string $country,
        string $prefix,
        string $number,
        string $password
    ) {
        try {
            $id = $this->pickPhoneUser->pick(null, $country, $prefix, $number)->getId();
        } catch (Userland\Phone\NonexistentUserException $e) {
            throw new InvalidAuthenticationException();
        }

        try {
            $privilegeUser = $this->pickPrivilegeUser->pick($id);
        } catch (Userland\Privilege\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            $passwordUser = $this->pickPasswordUser->pick($id);
        } catch (Userland\Password\NonexistentUserException $e) {
            throw new \LogicException(null, null, $e);
        }

        /* Check password */

        if (!$this->validatePassword->validate(
            $password,
            $passwordUser->getPassword()
        )) {
            throw new InvalidAuthenticationException();
        }

        /* Token */

        $token = (string) (new JWT\Builder())
            ->set('user', $id)
            ->sign(new JWT\Signer\Hmac\Sha256(), $this->jwtSecret)
            ->getToken();

        if (!in_array('reseller', $privilegeUser->getRoles())) {
            throw new InvalidAuthenticationException();
        }

        return new ResellerAuthentication(
            $id,
            $token,
            new Userland\Authentication\Phone(
                $country,
                $prefix,
                $number
            ),
            $privilegeUser->getRoles()
        );
    }
}