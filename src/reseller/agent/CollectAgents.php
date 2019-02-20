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
     * @param string $user
     *
     * @return Agents
     */
    public function collect(
        ?string $user
    ) {
        $criteria = [
            'deleted' => false
        ];

        if ($user) {
            $criteria['user'] = $user;
        }

        $cursor = $this->selectCollection->select()->find(
            $criteria,
            [
                'sort' => [
                    'name' => 1
                ],
            ]
        );

        return new Agents($cursor);
    }
}
