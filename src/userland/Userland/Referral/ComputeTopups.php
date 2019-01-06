<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

use Intermaple\Mundorecarga;
use MongoDB\BSON\UTCDateTime;
use Yosmy\PrepareAggregation;

/**
 * @di\service()
 */
class ComputeTopups
{
    const GROUP_BY_DAY = 'by-day';
    const GROUP_BY_MONTH = 'by-month';
    const GROUP_BY_YEAR = 'by-year';

    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @var Mundorecarga\CollectContacts
     */
    private $collectContacts;

    /***
     * @var Mundorecarga\SelectTopupCollection
     */
    private $selectTopupCollection;

    /***
     * @var SelectTopupCollection
     */
    private $selectReferralTopupCollection;

    /**
     * @var PrepareAggregation
     */
    private $prepareAggregation;

    /**
     * @param SelectUserCollection               $selectUserCollection
     * @param Mundorecarga\CollectContacts       $collectContacts
     * @param Mundorecarga\SelectTopupCollection $selectTopupCollection
     * @param SelectTopupCollection              $selectReferralTopupCollection
     * @param PrepareAggregation                 $prepareAggregation
     */
    public function __construct(
        SelectUserCollection $selectUserCollection,
        Mundorecarga\CollectContacts $collectContacts,
        Mundorecarga\SelectTopupCollection $selectTopupCollection,
        SelectTopupCollection $selectReferralTopupCollection,
        PrepareAggregation $prepareAggregation
    ) {
        $this->selectUserCollection = $selectUserCollection;
        $this->collectContacts = $collectContacts;
        $this->selectTopupCollection = $selectTopupCollection;
        $this->selectReferralTopupCollection = $selectReferralTopupCollection;
        $this->prepareAggregation = $prepareAggregation;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/referral/compute-topups"})
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

        $contacts = $this->collectContacts->collect(null, $user->getReferrals(), false);

        $ids = [];
        foreach ($contacts as $contact) {
            $ids[] = $contact->getId();
        }

        $criteria = [
            'steps' => Mundorecarga\Topup::STEP_TRANSFER_SUCCESS,
            'contact' => ['$in' => $ids]
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

        $response = $this->selectTopupCollection->select()
            ->aggregate(
                [
                    // Remove profit field
                    ['$project' => ['profit' => 0]],
                    // Filter by date
                    ['$match' => $criteria],
                    // Join
                    ['$lookup' => [
                        'from' => 'userland_referral_topups',
                        'localField' => '_id',
                        'foreignField' => '_id',
                        'as' => "profits"
                    ]],
                    // Move fields to root
                    ['$replaceRoot' => [
                        'newRoot' => ['$mergeObjects' => [
                            ['$arrayElemAt' => ['$profits', 0]],
                            '$$ROOT'
                        ]]
                    ]],
                    // Remove join
                    ['$project' => ['profits' => 0]],
                    // Group by date
                    ['$group' => [
                        '_id' => $date,
                        'topups' => [
                            '$sum' => 1
                        ],
                        'profit' => [
                            '$sum' => '$profit'
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

        return $this->prepareAggregation->prepare(
            $from,
            $to,
            $group,
            $response
        );
    }
}