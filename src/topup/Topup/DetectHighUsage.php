<?php

namespace Intermaple\Mundorecarga\Topup;

use Intermaple\Mundorecarga\CollectContacts;
use Intermaple\Mundorecarga\SelectTopupCollection;
use MongoDB\BSON\UTCDateTime;

/**
 * @di\service()
 */
class DetectHighUsage
{
    /**
     * @var CollectContacts
     */
    private $collectContacts;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @param CollectContacts $collectContacts
     * @param SelectTopupCollection $selectTopupCollection
     */
    public function __construct(
        CollectContacts $collectContacts,
        SelectTopupCollection $selectTopupCollection
    ) {
        $this->collectContacts = $collectContacts;
        $this->selectTopupCollection = $selectTopupCollection;
    }

    /**
     * @param string $owner
     * @param float  $amount
     *
     * @return bool
     */
    public function detect(string $owner, float $amount)
    {
        $contacts = [];
        foreach ($this->collectContacts->collect(null, [$owner], null) as $contact) {
            $contacts[] = $contact->getId();
        }

        try {
            $date = new \DateTime("-24 hours");
        } catch (\Exception $e) {
            throw new \LogicException(null, null, $e);
        }
        $date->format("Y-m-d H:i:s");

        $response = $this->selectTopupCollection->select()
            ->aggregate(
                [
                    ['$match' => [
                        'contact' => ['$in' => $contacts],
                        'date' => [
                            '$gte' => new UTCDateTime($date->getTimestamp() * 1000)
                        ]
                    ]],
                    ['$group' => [
                        '_id' => [
                            'year' => ['$year' => '$date'],
                            'month' => ['$month' => '$date'],
                            'day' => ['$dayOfMonth' => '$date']
                        ],
                        'amount' => [
                            '$sum' => '$amount'
                        ]
                    ]],
                ],
                [
                    'typeMap' => [
                        'root' => 'array',
                        'document' => 'array'
                    ],
                ]
            );

        $response = iterator_to_array($response);

        return $response && $response[0]['amount'] >= 20;
    }
}