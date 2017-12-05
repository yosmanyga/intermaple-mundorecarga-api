<?php

namespace Intermaple\Mundorecarga;

use Yosmy;

/**
 * @di\service()
 */
class Upgrade
{
    /**
     * @var Yosmy\OrderedUpgrade
     */
    private $orderedUpgrade;

    /**
     * @param Yosmy\OrderedUpgrade $orderedUpgrade
     */
    public function __construct(Yosmy\OrderedUpgrade $orderedUpgrade)
    {
        $this->orderedUpgrade = $orderedUpgrade;
    }

    /**
     * @cli\resolution({command: "/upgrade"})
     *
     * @return string[]
     */
    public function upgrade()
    {
        return $this->orderedUpgrade->upgrade();
    }
}