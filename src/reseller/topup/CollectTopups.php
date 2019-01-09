<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\BSON\UTCDateTime;
use Yosmy\Userland;

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
     * @param int   $from
     * @param int   $to
     * @param array $agents
     *
     * @return Topups
     */
    public function collect(
        int $from,
        int $to,
        array $agents
    ) {
        $criteria = $this->buildCriteria($from, $to, $agents);

        if (!$criteria) {
            return new Topups(new \ArrayIterator());
        }

        $cursor = $this->selectCollection->select()->find(
            $criteria,
            [
                'sort' => [
                    'date' => -1
                ],
            ]
        );

        return new Topups($cursor);
    }

    /**
     * @param int   $from
     * @param int   $to
     * @param array $agents
     *
     * @return array
     */
    private function buildCriteria(
        int $from,
        int $to,
        array $agents
    ) {
        $criteria = [];

        $criteria['date']['$gte'] = new UTCDateTime($from * 1000);

        $criteria['date']['$lt'] = new UTCDatetime($to * 1000);

        if ($agents) {
            $criteria['agent'] = ['$in' => $agents];
        }

        return $criteria;
    }
}
