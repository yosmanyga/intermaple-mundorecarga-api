<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\UpgradeCollectionsTo20190110\UpgradeEventCollection;
use Yosmy;

/**
 * @di\service({
 *   tags: [{
 *     name: 'yosmy.upgrade',
 *     key: '20190110'
 *   }]
 * })
 */
class UpgradeCollectionsTo20190110 implements Yosmy\Upgrade
{
    /**
     * @var UpgradeEventCollection
     */
    private $upgradeEventCollection;

    /**
     * @param UpgradeEventCollection $upgradeEventCollection
     */
    public function __construct(
        UpgradeEventCollection $upgradeEventCollection
    ) {
        $this->upgradeEventCollection = $upgradeEventCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade($last)
    {
        if ($last !== '20190109') {
            return false;
        }

        $this->upgradeEventCollection->upgrade();

        return true;
    }
}