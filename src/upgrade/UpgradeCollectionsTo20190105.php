<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\UpgradeCollectionsTo20190105\UpgradeEventCollection;
use Yosmy;

/**
 * @di\service({
 *   tags: [{
 *     name: 'yosmy.upgrade',
 *     key: '20190105'
 *   }]
 * })
 */
class UpgradeCollectionsTo20190105 implements Yosmy\Upgrade
{
    /**
     * @var UpgradeEventCollection
     */
    private $upgradeEventCollection;

    /**
     * @param UpgradeEventCollection  $upgradeEventCollection
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
        if ($last !== '20190104') {
            return false;
        }

        $this->upgradeEventCollection->upgrade();

        return true;
    }
}