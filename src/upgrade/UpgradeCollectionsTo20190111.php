<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\UpgradeCollectionsTo20190111\UpgradeErrorCollection;
use Yosmy;

/**
 * @di\service({
 *   tags: [{
 *     name: 'yosmy.upgrade',
 *     key: '20190111'
 *   }]
 * })
 */
class UpgradeCollectionsTo20190111 implements Yosmy\Upgrade
{
    /**
     * @var UpgradeErrorCollection
     */
    private $upgradeErrorCollection;

    /**
     * @param UpgradeErrorCollection $upgradeErrorCollection
     */
    public function __construct(
        UpgradeErrorCollection $upgradeErrorCollection
    ) {
        $this->upgradeErrorCollection = $upgradeErrorCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade($last)
    {
        if ($last !== '20190110') {
            return false;
        }

        $this->upgradeErrorCollection->upgrade();

        return true;
    }
}