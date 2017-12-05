<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190104;

use MongoDB\Client;
use Yosmy\Userland\Blacklist;
use Yosmy\Userland\Stripe;

/**
 * @di\service()
 */
class UpgradeFraudCollections
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var
     */
    private $db;

    /**
     * @di\arguments({
     *     uri: "%mongo_uri%",
     *     db:  "%mongo_db%"
     * })
     *
     * @param string     $uri
     * @param string     $db
     */
    public function __construct(
        string $uri,
        string $db
    ) {
        $this->uri = $uri;
        $this->db = $db;
    }

    public function upgrade()
    {
        $this->moveSessions();
        $this->moveUsers();
        $this->moveCards();
    }

    private function moveSessions()
    {
        $oldCollection = (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'userland_stripe_fraud_sessions',
                [
                    'typeMap' => array(
                        'root' => 'array',
                    ),
                ]
            );

        $newCollection = (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'userland_blacklist_sessions',
                [
                    'typeMap' => array(
                        'root' => 'array',
                    ),
                ]
            );

        $sessions = $oldCollection->find();

        foreach ($sessions as $session) {
            $newCollection->insertOne([
                '_id' => $session['_id']
            ]);
        }

        $oldCollection->drop();
    }

    private function moveUsers()
    {
        $oldCollection = (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'userland_stripe_fraud_users',
                [
                    'typeMap' => array(
                        'root' => 'array',
                    ),
                ]
            );

        $newCollection = (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'userland_blacklist_users',
                [
                    'typeMap' => array(
                        'root' => 'array',
                    ),
                ]
            );

        $users = $oldCollection->find();

        foreach ($users as $user) {
            $newCollection->insertOne([
                '_id' => $user['_id']
            ]);
        }

        $oldCollection->drop();
    }

    private function moveCards()
    {
        $oldCollection = (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'userland_stripe_fraud_cards',
                [
                    'typeMap' => array(
                        'root' => 'array',
                    ),
                ]
            );

        $newCollection = (new Client($this->uri))
            ->selectCollection(
                $this->db,
                'userland_stripe_blacklist_cards',
                [
                    'typeMap' => array(
                        'root' => 'array',
                    ),
                ]
            );

        $cards = $oldCollection->find();

        foreach ($cards as $card) {
            $newCollection->insertOne([
                '_id' => $card['_id']
            ]);
        }

        $oldCollection->drop();
    }
}