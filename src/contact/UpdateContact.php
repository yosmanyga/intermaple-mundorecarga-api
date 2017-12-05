<?php

namespace Intermaple\Mundorecarga;

use MongoDB\UpdateResult;

/**
 * @di\service()
 */
class UpdateContact
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
     * @http\resolution({method: "POST", path: "/update-contact"})
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
