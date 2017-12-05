<?php

namespace Intermaple\Mundorecarga;

use MongoDB\UpdateResult;

/**
 * @di\service()
 */
class DeleteContact
{
    /**
     * @var SelectContactCollection
     */
    private $selectCollection;

    /**
     * @param SelectContactCollection $selectCollection
     */
    public function __construct(
        SelectContactCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/delete-contact"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $id
     */
    public function delete(
        string $client,
        string $id
    ) {
        /** @var UpdateResult $result */
        $result = $this->selectCollection->select()->updateOne(
            [
                '_id' => $id,
                'user' => $client
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
