<?php

namespace Intermaple\Mundorecarga\Reseller;

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

    /**
     * @param CollectAgents $collectAgents
     * @param CollectTopups $collectTopups
     */
    public function __construct(
        CollectAgents $collectAgents,
        CollectTopups $collectTopups
    ) {
        $this->collectAgents = $collectAgents;
        $this->collectTopups = $collectTopups;
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
     * @return Topups
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
                    throw new \LogicException();
                }
            }
        } else {
            $agents = $ids;
        }

        return $this->collectTopups->collect(
            $from,
            $to,
            $agents
        );
    }
}
