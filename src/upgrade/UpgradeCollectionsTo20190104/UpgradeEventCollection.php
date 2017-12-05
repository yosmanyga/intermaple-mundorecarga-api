<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190104;

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
            if (!$event['contents']) {
                continue;
            }

            if (
                $event['contents'][0]['key'] == 'trace'
                && $event['contents'][0]['value'] == []
            ) {
                continue;
            }

            if (
                isset($event['contents'][0]['value'][0])
                && isset($event['contents'][0]['value'][0]['type'])
            ) {
                continue;
            }

            $this->selectCollection->select()->deleteOne([
                '_id' => $event['_id']
            ]);

            if (isset($event['contents'][0]['value'][0]['key'])) {
                $event['contents'][0]['value'] = [
                    [
                        'type' => $event['contents'][0]['value'][0]['key'],
                        'value' => $event['contents'][0]['value'][0]['value']
                    ]
                ];
            } else if (isset($event['contents'][0]['value']['user'])) {
                $event['contents'][0]['value'] = [
                    [
                        'type' => 'user',
                        'value' => $event['contents'][0]['value']['user']
                    ]
                ];
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