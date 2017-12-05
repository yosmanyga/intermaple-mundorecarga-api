<?php

namespace Intermaple\Mundorecarga;

use MongoDB\BSON\UTCDateTime;

/**
 * @di\service()
 */
class CollectTopups
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
     * @param array    $contacts
     * @param string[] $steps
     * @param int      $from
     * @param int      $to
     * @param int      $limit
     *
     * @return Topups
     */
    public function collect(
        ?array $contacts,
        ?array $steps,
        ?int $from,
        ?int $to,
        ?int $limit
    ) {
        $criteria = $this->buildCriteria($contacts, $steps, $from, $to);

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

        return new Topups($cursor);
    }

    /**
     * @param array $contacts
     *
     * @return int
     */
    public function count(
        array $contacts
    ) {
        $criteria = $this->buildCriteria($contacts, null, null, null);

        return $this->selectCollection->select()->countDocuments(
            $criteria
        );
    }

    /**
     * @param array $contacts
     * @param array $steps
     * @param int   $from
     * @param int   $to
     *
     * @return array
     */
    private function buildCriteria(
        ?array $contacts,
        ?array $steps,
        ?int $from,
        ?int $to
    ) {
        $criteria = [];

        if ($contacts !== null) {
            $criteria['contact'] = ['$in' => $contacts];
        }

        if ($steps !== null) {
            $criteria['steps'] = ['$in' => $steps];
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
