<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectAgents
{
    /**
     * @var SelectAgentCollection
     */
    private $selectCollection;

    /**
     * @param SelectAgentCollection $selectCollection
     */
    public function __construct(
        SelectAgentCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-agents"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     *
     * @return Agents
     */
    public function collect(
        string $reseller
    ) {
        $cursor = $this->selectCollection->select()->find([
            'user' => $reseller,
            'deleted' => false
        ], [
            'sort' => [
                'name' => 1
            ],
        ]);

        return new Agents($cursor);
    }
}
