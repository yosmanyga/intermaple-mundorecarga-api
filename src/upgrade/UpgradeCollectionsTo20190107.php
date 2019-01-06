<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\UpgradeCollectionsTo20190107\UpgradeReferralCollection;
use Yosmy;

/**
 * @di\service({
 *   tags: [{
 *     name: 'yosmy.upgrade',
 *     key: '20190107'
 *   }]
 * })
 */
class UpgradeCollectionsTo20190107 implements Yosmy\Upgrade
{
    /**
     * @var UpgradeReferralCollection
     */
    private $upgradeReferralCollection;

    /**
     * @param UpgradeReferralCollection  $upgradeReferralCollection
     */
    public function __construct(
        UpgradeReferralCollection $upgradeReferralCollection
    ) {
        $this->upgradeReferralCollection = $upgradeReferralCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade($last)
    {
        if ($last !== '20190106') {
            return false;
        }

        $this->upgradeReferralCollection->upgrade();

        return true;
    }
}