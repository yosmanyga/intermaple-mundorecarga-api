<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\UpgradeCollectionsTo20190108\UpgradeContactCollection;
use Yosmy;

/**
 * @di\service({
 *   tags: [{
 *     name: 'yosmy.upgrade',
 *     key: '20190108'
 *   }]
 * })
 */
class UpgradeCollectionsTo20190108 implements Yosmy\Upgrade
{
    /**
     * @var UpgradeContactCollection
     */
    private $upgradeContactCollection;

    /**
     * @param UpgradeContactCollection $upgradeContactCollection
     */
    public function __construct(
        UpgradeContactCollection $upgradeContactCollection
    ) {
        $this->upgradeContactCollection = $upgradeContactCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade($last)
    {
        if ($last !== '20190107') {
            return false;
        }

        $this->upgradeContactCollection->upgrade();

        return true;
    }
}