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
     * @return Promotion[]
     */
    public function collect($providers, $start)
    {
        /** @var Recharge\Ding\Promotion[] $cursor */
        $cursor = $this->collectPromotions->collect($providers, $start);

        $promotions = [];
        foreach ($cursor as $promotion) {
            $headline = str_replace(['Ezetop', 'ezetop', 'Ding', 'ding'], 'MundoRecarga', $promotion['headline']);
            $terms = str_replace(['Ezetop', 'ezetop', 'Ding', 'ding'], 'MundoRecarga', $promotion['terms']);

            $promotions[] = new Promotion(array_merge(
                $promotion->getArrayCopy(),
                [
                    'headline' => $headline,
                    'terms' => $terms,
                ]
            ));
        }

        return $promotions;
    }
}
