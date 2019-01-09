<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190108;

use Intermaple\Mundorecarga\SelectContactCollection;
use Intermaple\Mundorecarga\Userland\Referral;

/**
 * @di\service()
 */
class UpgradeContactCollection
{
    /**
     * @var SelectContactCollection
     */
    private $selectContactCollection;

    /**
     * @param SelectContactCollection $selectContactCollection
     */
    public function __construct(
        SelectContactCollection $selectContactCollection
    ) {
        $this->selectContactCollection = $selectContactCollection;
    }

    public function upgrade()
    {
        $contacts = $this->selectContactCollection->select()->find([], [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array'
            ],
        ]);

        foreach ($contacts as $contact) {
            $this->selectContactCollection->select()->updateOne(
                [
                    '_id' => $contact['_id']
                ],
                [
                    '$set' => [
                        'type' => 'phone'
                    ]
                ]
            );
        }
    }
}