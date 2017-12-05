<?php

namespace Intermaple\Mundorecarga;

use MongoDB\BSON\UTCDateTime;

/**
 * @di\service()
 */
class ComputeTopups
{
    /**
     * @var SelectTopupCollection
     */
    private $selectCollection;

    /**
     * @param SelectTopupCollection $selectCollection
     */
    public function __construct(
        SelectTopupCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param array $contacts
     * @param int   $from
     * @param int   $to
     *
     * @return Topups
     */
    public function collect(
        ?array $contacts,
        ?$from,
        ?$to
    ) {
        $criteria = [];

        if ($contacts !== null) {
            $criteria['contact'] = ['$in' => $contacts];
        }

        if ($from !== null) {
            $criteria['date']['$gte'] = new UTCDateTime($from * 1000);
        }

        if ($to !== null) {
            $criteria['date']['$lt'] = new UTCDatetime($to * 1000);
        }

        $cursor = $this->selectCollection->select()->aggregate(
            [
                ['$project' => [
                    'amount' => 1,
                    'date' => 1,
                ]],
                ['$match' => $criteria],
                ['$group' => [
                    '_id' => [
                        'year' => ['$year' => '$date'],
                        'month' => ['$month' => '$date'],
                        'day' => ['$dayOfMonth' => '$date']
                    ],
                    'total' => [
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

        return new Topups($cursor);
    }
}
