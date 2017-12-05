<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\UpgradeCollectionsTo20190104\UpgradeEventCollection;
use Intermaple\Mundorecarga\UpgradeCollectionsTo20190104\UpgradeFraudCollections;
use Yosmy;

/**
 * @di\service({
 *   tags: [{
 *     name: 'yosmy.upgrade',
 *     key: '20190104'
 *   }]
 * })
 */
class UpgradeCollectionsTo20190104 implements Yosmy\Upgrade
{
    /**
     * @var UpgradeEventCollection
     */
    private $upgradeEventCollection;

    /**
     * @var UpgradeFraudCollections
     */
    private $upgradeFraudCollections;

    /**
     * @param UpgradeEventCollection  $upgradeEventCollection
     * @param UpgradeFraudCollections $upgradeFraudCollections
     */
    public function __construct(
        UpgradeEventCollection $upgradeEventCollection,
        UpgradeFraudCollections $upgradeFraudCollections
    ) {
        $this->upgradeEventCollection = $upgradeEventCollection;
        $this->upgradeFraudCollections = $upgradeFraudCollections;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade($last)
    {
        if ($last !== '20190103') {
            return false;
        }

        $this->upgradeEventCollection->upgrade();
        $this->upgradeFraudCollections->upgrade();

        return true;
    }
}