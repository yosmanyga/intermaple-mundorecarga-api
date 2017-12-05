<?php

namespace Intermaple\Mundorecarga\Userland;

use Intermaple\Mundorecarga\Userland\Referral;
use Yosmy\Userland;

/**
 * @di\service()
 */
class CompleteAuthentication
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var Userland\CompleteAuthentication
     */
    private $completeAuthentication;

    /**
     * @var Referral\PickUser
     */
    private $pickReferralUser;

    /**
     * @var Referral\User\AddReferral
     */
    private $addReferral;

    /**
     * @di\arguments({
     *     env: "%env%"
     * })
     *
     * @param string                          $env
     * @param Userland\CompleteAuthentication $completeAuthentication
     * @param Referral\PickUser               $pickReferralUser
     * @param Referral\User\AddReferral       $addReferral
     */
    public function __construct(
        string $env,
        Userland\CompleteAuthentication $completeAuthentication,
        Referral\PickUser $pickReferralUser,
        Referral\User\AddReferral $addReferral
    ) {
        $this->env = $env;
        $this->completeAuthentication = $completeAuthentication;
        $this->pickReferralUser = $pickReferralUser;
        $this->addReferral = $addReferral;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/complete-authentication"})
     *
     * @param string $referral
     * @param string $session
     * @param string $country
     * @param string $prefix
     * @param string $number
     * @param string $code
     *
     * @return Authentication
     *
     * @throws Authentication\InvalidCodeException
     */
    public function complete(
        ?string $referral,
        ?string $session,
        string $country,
        string $prefix,
        string $number,
        string $code
    ) {
        if (
            $this->env == 'dev'
        ) {
            $authentication = $this->completeAuthentication->completeWithoutSms($session, $country, $prefix, $number, ['client']);
        } else {
            try {
                $authentication = $this->completeAuthentication->complete($session, $country, $prefix, $number, $code, ['client']);
            } catch (Userland\Authentication\InvalidCodeException $e) {
                throw new Authentication\InvalidCodeException();
            }
        }

        if ($referral) {
            $code = null;

            try {
                $user = $this->pickReferralUser->pick($authentication->getId());

                $code = $user->getCode();
            } catch (Referral\NonexistentUserException $e) {
                // Not in the referral program yet?
                // Ignore it
            }

            if (
                $code == null
                // Is not the user adding himself?
                || $code != $referral) {
                $this->addReferral->add($authentication->getId(), $referral);
            }
        }

        return new Authentication(
            $authentication->getId(),
            $authentication->getSession(),
            $authentication->getToken(),
            $authentication->getRoles()
        );
    }
}