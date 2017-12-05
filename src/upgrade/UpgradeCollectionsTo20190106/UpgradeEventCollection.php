<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190106;

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
            if (
                !isset($event['contents'][0])
                || !isset($event['contents'][0]['value'])
                || !isset($event['contents'][0]['value'][0])
            ) {
                continue;
            }

            $this->selectCollection->select()->deleteOne([
                '_id' => $event['_id']
            ]);

            $event['contents'][0]['value'] = $event['contents'][0]['value'][0];

            $this->selectCollection->select()->insertOne([
                '_id' => $event['_id'],
                'labels' => $event['labels'],
                'contents' => $event['contents'],
                'date' => $event['date']
            ]);
        }

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

            if (isset($event['contents'][0]['value']['session'])) {
                $event['contents'][0]['value'] = [
                    'type' => 'session',
                    'value' => $event['contents'][0]['value']['session']
                ];
            }

            if (isset($event['contents'][0]['value']['user'])) {
                $event['contents'][0]['value'] = [
                    'type' => 'user',
                    'value' => $event['contents'][0]['value']['user']
                ];
            }

            if (isset($event['contents'][0]['value']['card'])) {
                $event['contents'][0]['value'] = [
                    'type' => 'card',
                    'value' => $event['contents'][0]['value']['card']
                ];
            }

            if (isset($event['contents'][0]['value']['contact'])) {
                $event['contents'][0]['value'] = [
                    'type' => 'contact',
                    'value' => $event['contents'][0]['value']['contact']
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