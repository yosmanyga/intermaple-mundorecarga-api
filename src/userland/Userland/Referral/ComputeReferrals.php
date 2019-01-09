<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

use MongoDB\BSON\UTCDateTime;
use Yosmy\PrepareAggregation;
use Yosmy\Userland;

/**
 * @di\service()
 */
class ComputeReferrals
{
    const GROUP_BY_DAY = 'by-day';
    const GROUP_BY_MONTH = 'by-month';
    const GROUP_BY_YEAR = 'by-year';

    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;

    /***
     * @var Userland\Registration\SelectUserCollection
     */
    private $selectRegistrationUserCollection;

    /**
     * @var PrepareAggregation
     */
    private $prepareAggregation;

    /**
     * @param SelectUserCollection                       $selectUserCollection
     * @param Userland\Registration\SelectUserCollection $selectRegistrationUserCollection
     * @param PrepareAggregation                         $prepareAggregation
     */
    public function __construct(
        SelectUserCollection $selectUserCollection,
        Userland\Registration\SelectUserCollection $selectRegistrationUserCollection,
        PrepareAggregation $prepareAggregation
    ) {
        $this->selectUserCollection = $selectUserCollection;
        $this->selectRegistrationUserCollection = $selectRegistrationUserCollection;
        $this->prepareAggregation = $prepareAggregation;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/referral/compute-referrals"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param int    $from
     * @param int    $to
     * @param string $group
     *
     * @return array
     */
    public function collect(
        string $client,
        int $from,
        int $to,
        string $group
    ) {
        /** @var User $user */
        $user = $this->selectUserCollection->select()->findOne([
            '_id' => $client
        ]);

        $criteria = [
            '_id' => ['$in' => $user->getReferrals()]
        ];

        if ($from !== null) {
            $criteria['date']['$gte'] = new UTCDateTime($from * 1000);
        }

        if ($to !== null) {
            $criteria['date']['$lt'] = new UTCDatetime($to * 1000);
        }

        switch ($group) {
            case self::GROUP_BY_DAY:
                $date = [
                    'year' => ['$year' => '$date'],
                    'month' => ['$month' => '$date'],
                    'day' => ['$dayOfMonth' => '$date']
                ];

                break;
            case self::GROUP_BY_MONTH:
                $date = [
                    'year' => ['$year' => '$date'],
                    'month' => ['$month' => '$date']
                ];

                break;
            case self::GROUP_BY_YEAR:
                $date = [
                    'year' => ['$year' => '$date']
                ];

                break;
            default:
                $date = [
                    'year' => ['$year' => '$date'],
                    'month' => ['$month' => '$date'],
                    'day' => ['$dayOfMonth' => '$date']
                ];
        }

        $response = $this->selectRegistrationUserCollection->select()
            ->aggregate(
                [
                    // Filter by date
                    ['$match' => $criteria],
                    // Group by date
                    ['$group' => [
                        '_id' => $date,
                        'referrals' => [
                            '$sum' => 1
                        ],
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

        if (!$response) {
            switch ($group) {
                case self::GROUP_BY_DAY:
                    $id = [
                        'year' => date('Y', $from),
                        'month' => date('n', $from),
                        'day' => date('j', $from),
                    ];

                    break;
                case self::GROUP_BY_MONTH:
                    $id = [
                        'year' => date('Y', $from),
                        'month' => date('n', $from),
                    ];

                    break;
                case self::GROUP_BY_YEAR:
                    $id = [
                        'year' => date('Y', $from),
                    ];

                    break;
                default:
                    $id = [
                        'year' => date('Y', $from),
                        'month' => date('m', $from),
                        'day' => date('j', $from),
                    ];
            }

            $response = [
                [
                    '_id' => $id,
                    'referrals' => 0,
                ]
            ];
        }

        return $this->prepareAggregation->prepare(
            $from,
            $to,
            $group,
            $response
        );
    }
}