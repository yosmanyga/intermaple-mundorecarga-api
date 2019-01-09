<?php

namespace Intermaple\Mundorecarga\Blacklist;

use MongoDB\Driver\Exception\BulkWriteException;
use Yosmy\Recharge;
use Yosmy\Log;
use Intermaple\Mundorecarga\Contact;

/**
 * @di\service({
 *     lazy: true
 * })
 */
class BanContact
{
    /**
     * @var SelectContactCollection
     */
    private $selectCollection;

    /**
     * @var Log\AddEvent
     */
    private $addEvent;

    /**
     * @var callable[]
     */
    private $listeners;

    /**
     * @di\arguments({
     *     listeners: '#blacklist.contact_banned'
     * })
     *
     * @param SelectContactCollection $selectCollection
     * @param Log\AddEvent            $addEvent
     * @param callable[]              $listeners
     */
    public function __construct(
        SelectContactCollection $selectCollection,
        Log\AddEvent $addEvent,
        array $listeners
    ) {
        $this->selectCollection = $selectCollection;
        $this->addEvent = $addEvent;
        $this->listeners = $listeners;
    }

    /**
     * @param Contact $contact
     * @param array $trace
     */
    public function ban(
        Contact $contact,
        array $trace
    ) {
        try {
            $this->selectCollection->select()->insertOne([
                '_id' => sprintf("%s-%s", $contact->getPrefix(), $contact->getAccount())
            ]);
        } catch (BulkWriteException $e) {
            $error = $e->getWriteResult()->getWriteErrors()[0];

            if ($error->getCode() == 11000) {
                if (strpos($error->getMessage(), 'index: _id_') !== false) {
                    // It's already banned
                    return;
                }
            }

            throw $e;
        }

        $this->addEvent->add(
            [
                new Log\Event\Label('type', 'blacklist.ban_contact'),
                new Log\Event\Label('user', $contact->getUser()),
                new Log\Event\Label('contact', $contact->getId())
            ],
            [
                new Log\Event\Content('trace', $trace)
            ]
        );

        foreach ($this->listeners as $listener) {
            $listener(
                $contact->getId()
            );
        }
    }
}
