<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190111;

use Yosmy\Userland\Stripe\Card\SelectErrorCollection;

/**
 * @di\service()
 */
class UpgradeErrorCollection
{
    /**
     * @var SelectErrorCollection
     */
    private $selectCollection;

    /**
     * @param SelectErrorCollection $selectCollection
     */
    public function __construct(SelectErrorCollection $selectCollection)
    {
        $this->selectCollection = $selectCollection;
    }

    public function upgrade()
    {
        $errors = iterator_to_array($this->selectCollection->select()->find([], [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array'
            ],
        ]));

        foreach ($errors as $error) {
            $this->selectCollection->select()->deleteOne([
                '_id' => $error['_id']
            ]);

            $this->selectCollection->select()->insertOne([
                '_id' => $error['_id'],
                'user' => $error['user'],
                'message' => $error['message'],
                'payload' => $error['payload'],
                'stripe' => $error['raw'],
                'date' => $error['date']
            ]);
        }
    }
}