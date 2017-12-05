<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190105;

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

            foreach ($event['labels'] as $i => $label) {
                if ($label['key'] != 'type') {
                    continue;
                }

                if (
                    $label['value'] == 'stripe.create_token'
                    || $label['value'] == 'stripe.add_card_to_customer'
                    || $label['value'] == 'stripe.create_customer_with_card'
                    || $label['value'] == 'stripe.delete_card'
                ) {
                    continue 2;
                }

                if ($label['value'] == 'stripe.fraud.ban_card') {
                    $event['labels'][$i]['value'] = 'yosmy.userland.stripe.blacklist.ban_card';
                }

                if ($label['value'] == 'stripe.fraud.ban_user') {
                    $event['labels'][$i]['value'] = 'yosmy.userland.blacklist.ban_user';
                }

                if ($label['value'] == 'stripe.fraud.ban_session') {
                    $event['labels'][$i]['value'] = 'yosmy.userland.blacklist.ban_session';
                }

                if ($event['contents'][0]['value']) {
                    $event['contents'][0]['value'] = [
                        $event['contents'][0]['value'][count($event['contents'][0]['value']) - 1]
                    ];
                }
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