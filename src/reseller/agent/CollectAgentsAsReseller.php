<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectAgentsAsReseller
{
    /**
     * @var CollectAgents
     */
    private $collectAgents;

    /**
     * @param CollectAgents $collectAgents
     */
    public function __construct(
        CollectAgents $collectAgents
    ) {
        $this->collectAgents = $collectAgents;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-agents-as-reseller"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     *
     * @return Agents
     */
    public function collect(
        ?string $reseller
    ) {
        return $this->collectAgents->collect($reseller);
    }
}
