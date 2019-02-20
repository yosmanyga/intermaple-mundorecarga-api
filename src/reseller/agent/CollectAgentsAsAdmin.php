<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectAgentsAsAdmin
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
     * @http\resolution({method: "POST", path: "/reseller/collect-agents-as-admin"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @return Agents
     */
    public function collect() {
        return $this->collectAgents->collect(null);
    }
}
