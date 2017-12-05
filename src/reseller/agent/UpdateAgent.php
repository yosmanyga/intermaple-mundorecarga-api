<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\UpdateResult;

/**
 * @di\service()
 */
class UpdateAgent
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
     * @http\resolution({method: "POST", path: "/update-agent"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $id
     * @param string $name
     */
    public function update(
        string $client,
        string $id,
        string $name
    ) {
        /** @var UpdateResult $result */
        $result = $this->selectCollection->select()->updateOne(
            [
                '_id' => $id,
                'user' => $client
            ],
            ['$set' => ['name' => $name]]
        );

        if ($result->getMatchedCount() === 0) {
            throw new \LogicException();
        }
    }
}
