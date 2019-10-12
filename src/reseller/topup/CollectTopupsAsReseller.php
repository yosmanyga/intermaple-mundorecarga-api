<?php

namespace Intermaple\Mundorecarga\Reseller;

use Intermaple\Mundorecarga\NonexistentProductException;
use Intermaple\Mundorecarga\PickProduct;
use LogicException;

/**
 * @di\service()
 */
class CollectTopupsAsReseller
{
    /**
     * @var CollectAgents
     */
    private $collectAgents;

    /**
     * @var CollectTopups
     */
    private $collectTopups;

    /***
     * @var PickProduct
     */
    private $pickProduct;

    /**
     * @var PickProvider
     */
    private $pickProvider;

    /**
     * @param CollectAgents $collectAgents
     * @param CollectTopups $collectTopups
     * @param PickProduct $pickProduct
     * @param PickProvider $pickProvider
     */
    public function __construct(
        CollectAgents $collectAgents,
        CollectTopups $collectTopups,
        PickProduct $pickProduct,
        PickProvider $pickProvider
    ) {
        $this->collectAgents = $collectAgents;
        $this->collectTopups = $collectTopups;
        $this->pickProduct = $pickProduct;
        $this->pickProvider = $pickProvider;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-topups-as-reseller"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     * @param int    $from
     * @param int    $to
     * @param array  $agents
     *
     * @return Topup[]
     */
    public function collect(
        string $reseller,
        int $from,
        int $to,
        array $agents
    ) {
        /* Verify agents */

        $ids = [];
        foreach ($this->collectAgents->collect($reseller) as $agent) {
            $ids[] = $agent->getId();
        }

        if ($agents) {
            foreach ($agents as $agent) {
                if (!in_array($agent, $ids)) {
                    throw new LogicException();
                }
            }
        } else {
            $agents = $ids;
        }

        $topups = $this->collectTopups->collect(
            $from,
            $to,
            $agents
        );

        $resellerTopups = [];
        foreach ($topups as $topup) {
            try {
                $product = $this->pickProduct->pick($topup->getProduct());
            } catch (NonexistentProductException $e) {
                throw new LogicException(null, null, $e);
            }

            $provider = $this->pickProvider->pick(
                $product->getProvider(),
                $reseller
            );

            $discount = $topup->getAmount() * $provider->getDiscount() / 100;

            $charge = $topup->getAmount() - $discount;

            $resellerTopups[] = new Topup([
                'id' => $topup->getId(),
                'agent' => $topup->getAgent(),
                'account' => $topup->getAccount(),
                'product' => $topup->getProduct(),
                'amount' => $topup->getAmount(),
                'ding' => $topup->getDing(),
                'attempts' => $topup->getAttempts(),
                'charge' => $charge,
                'date' => $topup->getDate(),
                'steps' => $topup->getSteps(),
                'receive' => $topup->getReceive(),
                'currency' => $topup->getCurrency(),
            ]);
        }

        return $resellerTopups;
    }
}
