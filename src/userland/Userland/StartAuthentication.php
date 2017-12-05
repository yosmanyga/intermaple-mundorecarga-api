<?php

namespace Intermaple\Mundorecarga\Userland;

use Yosmy\Userland;

/**
 * @di\service()
 */
class StartAuthentication
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var Userland\StartAuthentication
     */
    private $startAuthentication;

    /**
     * @di\arguments({
     *     env: "%env%"
     * })
     *
     * @param string                       $env
     * @param Userland\StartAuthentication $startAuthentication
     */
    public function __construct(
        string $env,
        Userland\StartAuthentication $startAuthentication
    ) {
        $this->env = $env;
        $this->startAuthentication = $startAuthentication;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/start-authentication"})
     *
     * @param string $prefix
     * @param string $number
     *
     * @throws Authentication\InvalidNumberException
     */
    public function start(
        string $prefix,
        string $number
    ) {
        if (
            $this->env == 'dev'
        ) {
            $this->startAuthentication->startWithoutSms($prefix, $number);
        } else {
            try {
                $this->startAuthentication->start($prefix, $number);
            } catch (Userland\Authentication\InvalidNumberException $e) {
                throw new Authentication\InvalidNumberException();
            }
        }
    }
}