<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\UpdateResult;

/**
 * @di\service()
 */
class DeleteAgent
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
     * @http\resolution({method: "POST", path: "/reseller/delete-agent"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     * @param string $id
     */
    public function add(
        string $reseller,
        string $id
    ) {
        /** @var UpdateResult $result */
        $result = $this->selectCollection->select()->updateOne(
            [
                '_id' => $id,
                'user' => $reseller
            ],
            ['$set' => [
                'deleted' => true]
            ]
        );

        if ($result->getMatchedCount() === 0) {
            throw new \LogicException();
        }
    }
}
