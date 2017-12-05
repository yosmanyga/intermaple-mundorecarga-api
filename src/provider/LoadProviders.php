<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class LoadProviders
{
    /**
     * @var Recharge\Ding\LoadProviders
     */
    private $loadProviders;

    /**
     * @param Recharge\Ding\LoadProviders $loadProviders
     */
    public function __construct(Recharge\Ding\LoadProviders $loadProviders)
    {
        $this->loadProviders = $loadProviders;
    }

    /**
     * @cli\resolution({command: "/load-providers"})
     */
    public function load()
    {
        $this->loadProviders->load();
    }
}
