<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190109;

use Yosmy\Log\SelectEventCollection;

/**
 * @di\service()
 */
class UpgradeEventCollection
{
    /**
     * @var SelectEventCollection
     */
    private $selectCollection;

    /**
     * @param SelectEventCollection $selectCollection
     */
    public function __construct(SelectEventCollection $selectCollection)
    {
        $this->selectCollection = $selectCollection;
    }

    public function upgrade()
    {
        $events = iterator_to_array($this->selectCollection->select()->find([], [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array'
            ],
        ]));

        foreach ($events as $event) {
            $this->selectCollection->select()->deleteOne([
                '_id' => $event['_id']
            ]);

            if (!isset($event['contents'][0])) {
                continue;
            }

            if ($event['contents'][0]['value']['type'] == 'imported-dispute') {
                $event['contents'][0]['value']['type'] = 'dispute-imported';
            } else if ($event['contents'][0]['value']['type'] == 'banned-user') {
                $event['contents'][0]['value']['type'] = 'user-banned';
            } else if ($event['contents'][0]['value']['type'] == 'blocked-charge') {
                $event['contents'][0]['value']['type'] = 'charge-blocked';
            } else if ($event['contents'][0]['value']['type'] == 'banned-card') {
                $event['contents'][0]['value']['type'] = 'card-banned';
            } else if ($event['contents'][0]['value']['type'] == 'different-country') {
                $event['contents'][0]['value']['type'] = 'country-different';
            }

            $this->selectCollection->select()->insertOne([
                '_id' => $event['_id'],
                'labels' => $event['labels'],
                'contents' => $event['contents'],
                'date' => $event['date']
            ]);
        }
    }
}