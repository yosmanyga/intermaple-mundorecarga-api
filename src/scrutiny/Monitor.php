<?php

namespace Intermaple\Mundorecarga\Scrutiny;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class Monitor
{
    /**
     * @var string
     */
    private $dingConnectUsername;

    /**
     * @var string
     */
    private $dingConnectPassword;

    /**
     * @var Recharge\Ding\QueryApi
     */
    private $queryApi;

    /**
     * @di\arguments({
     *     dingConnectUsername: "%ding_connect_username%",
     *     dingConnectPassword: "%ding_connect_password%",
     * })
     *
     * @param string $dingConnectUsername
     * @param string $dingConnectPassword
     * @param Recharge\Ding\QueryApi $queryApi
     */
    public function __construct(
        string $dingConnectUsername,
        string $dingConnectPassword,
        Recharge\Ding\QueryApi $queryApi
    ) {
        $this->dingConnectUsername = $dingConnectUsername;
        $this->dingConnectPassword = $dingConnectPassword;
        $this->queryApi = $queryApi;
    }

    /**
     * @cli\resolution({command: "/monitor"})
     */
    public function monitor()
    {

    }
}
