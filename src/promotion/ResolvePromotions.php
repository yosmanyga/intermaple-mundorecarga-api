<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class ResolvePromotions
{
    /**
     * @var Recharge\Ding\ResolvePromotions
     */
    private $resolvePromotions;

    /**
     * @param Recharge\Ding\ResolvePromotions $resolvePromotions
     */
    public function __construct(Recharge\Ding\ResolvePromotions $resolvePromotions)
    {
        $this->resolvePromotions = $resolvePromotions;
    }

    /**
     * @http\resolution({method: "POST", path: "/resolve-promotions"})
     * @cli\resolution({command: "/resolve-promotions"})
     *
     * @param string $prefix
     * @param string $account
     * @param string $provider
     *
     * @return Promotions
     */
    public function resolve($prefix, $account, $provider)
    {
        try {
            $promotions = new Promotions(
                $this->resolvePromotions->resolve($prefix, $account, $provider)->getIterator()
            );
        } catch (Recharge\Ding\AccountException $e) {
            throw new \LogicException(null, null, $e);
        }

        return $promotions;
    }
}
