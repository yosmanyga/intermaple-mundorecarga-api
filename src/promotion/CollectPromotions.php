<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class CollectPromotions
{
    /**
     * @var Recharge\Ding\CollectPromotions
     */
    private $collectPromotions;

    /**
     * @param Recharge\Ding\CollectPromotions $collectPromotions
     */
    public function __construct(Recharge\Ding\CollectPromotions $collectPromotions)
    {
        $this->collectPromotions = $collectPromotions;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-promotions"})
     *
     * @param array $providers
     * @param int   $start
     *
     * @return Promotions
     */
    public function collect($providers, $start)
    {
        $promotions = new Promotions(
            $this->collectPromotions->collect($providers, $start)->getIterator()
        );

        return $promotions;
    }
}
