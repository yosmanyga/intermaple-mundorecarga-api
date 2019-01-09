<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190110;

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
            if (!isset($event['contents'][0])) {
                continue;
            }

            if ($event['contents'][0]['value']['type'] != 'contact-banned') {
                continue;
            }

            $this->selectCollection->select()->deleteOne([
                '_id' => $event['_id']
            ]);

            $event['contents'][0]['value']['value'] = $event['contents'][0]['value']['value']['id'];

            $this->selectCollection->select()->insertOne([
                '_id' => $event['_id'],
                'labels' => $event['labels'],
                'contents' => $event['contents'],
                'date' => $event['date']
            ]);
        }
    }
}