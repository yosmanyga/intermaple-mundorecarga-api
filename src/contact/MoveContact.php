<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service({private: true})
 */
class MoveContact
{
    /**
     * @var SelectContactCollection
     */
    private $selectCollection;

    /**
     * @param SelectContactCollection $selectCollection
     */
    public function __construct(
        SelectContactCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param Contact  $contact
     * @param Provider $provider
     */
    public function move(
        Contact $contact,
        Provider $provider
    ) {
        $this->selectCollection->select()->updateOne(
            [
                '_id' => $contact->getId(),
            ],
            ['$set' => ['provider' => $provider->getId()]]
        );
    }
}
