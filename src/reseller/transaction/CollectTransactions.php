<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\BSON\UTCDateTime;

/**
 * @di\service()
 */
class CollectTransactions
{
    /**
     * @var SelectTransactionCollection
     */
    private $selectCollection;

    /**
     * @param SelectTransactionCollection $selectCollection
     */
    public function __construct(
        SelectTransactionCollection $selectCollection
    )
    {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string $user
     * @param int    $from
     * @param int    $to
     * @param int    $limit
     *
     * @return Transactions
     */
    public function collect(
        ?string $user,
        ?int $from,
        ?int $to,
        ?int $limit
    ) {
        $criteria = $this->buildCriteria($user, $from, $to);

        if ($limit === null) {
            $limit = 200;
        }

        $cursor = $this->selectCollection->select()->find(
            $criteria,
            [
                'limit' => $limit,
                'sort' => [
                    'date' => -1
                ],
            ]
        );

        return new Transactions($cursor);
    }

    /**
     * @param string $user
     * @param int    $from
     * @param int    $to
     *
     * @return array
     */
    private function buildCriteria(
        ?string $user,
        ?int $from,
        ?int $to
    ) {
        $criteria = [];

        if ($user !== null) {
            $criteria['user'] = $user;
        }

        if ($from !== null) {
            $criteria['date']['$gte'] = new UTCDateTime($from * 1000);
        }

        if ($to !== null) {
            $criteria['date']['$lt'] = new UTCDatetime($to * 1000);
        }

        return $criteria;
    }
}
