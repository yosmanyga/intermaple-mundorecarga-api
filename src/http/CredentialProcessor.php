<?php

namespace Intermaple\Mundorecarga\Http;

use Symsonte\Http\Server\Request\Authentication\Credential\Processor as BaseCredentialProcessor;
use Symsonte\Http\Server\Request\Authentication\Credential\AuthorizationResolver;
use Symsonte\Http\Server\Request\Authentication\Credential\UnresolvableException;
use Symsonte\Http\Server\Request\Authentication\InvalidCredentialException;
use Yosmy;

/**
 * @di\service({
 *     private: true
 * })
 */
class CredentialProcessor implements BaseCredentialProcessor
{
    /**
     * @var AuthorizationResolver
     */
    private $authorizationResolver;

    /**
     * @var Yosmy\Userland\VerifyAuthentication
     */
    private $verifyAuthentication;

    /**
     * @param AuthorizationResolver $authorizationResolver
     * @param Yosmy\Userland\VerifyAuthentication $verifyAuthentication
     */
    public function __construct(
        AuthorizationResolver $authorizationResolver,
        Yosmy\Userland\VerifyAuthentication $verifyAuthentication
    ) {
        $this->authorizationResolver = $authorizationResolver;
        $this->verifyAuthentication = $verifyAuthentication;
    }

    /**
     * {@inheritdoc}
     */
    public function process()
    {
        try {
            $credential = $this->authorizationResolver->resolve();
        } catch (UnresolvableException $e) {
            throw new \LogicException(null, null, $e);
        }

        try {
            return $this->verifyAuthentication->verify($credential->getToken());
        } catch (Yosmy\Userland\Authentication\InvalidTokenException $e) {
            throw new InvalidCredentialException();
        }
    }
}
