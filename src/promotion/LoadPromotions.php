<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class LoadPromotions
{
    /**
     * @var Recharge\Ding\LoadPromotions
     */
    private $loadPromotions;

    /**
     * @param Recharge\Ding\LoadPromotions $loadPromotions
     */
    public function __construct(Recharge\Ding\LoadPromotions $loadPromotions)
    {
        $this->loadPromotions = $loadPromotions;
    }

    /**
     * @cli\resolution({command: "/load-promotions"})
     */
    public function load()
    {
        $this->loadPromotions->load();
    }
}
