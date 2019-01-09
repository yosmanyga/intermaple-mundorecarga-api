<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service({private: true})
 */
class AddContact
{
    /**
     * @var SelectContactCollection
     */
    private $selectCollection;

    /**
     * @var callable[]
     */
    private $listeners;

    /**
     * @di\arguments({
     *     listeners: '#contact_added'
     * })
     *
     * @param SelectContactCollection $selectCollection
     * @param callable[]              $listeners
     */
    public function __construct(
        SelectContactCollection $selectCollection,
        array $listeners
    ) {
        $this->selectCollection = $selectCollection;
        $this->listeners = $listeners;
    }

    /**
     * @param string   $user
     * @param Country  $country
     * @param string   $account
     * @param string   $type
     * @param Provider $provider
     *
     * @return Contact
     */
    public function add(
        string $user,
        Country $country,
        string $account,
        string $type,
        Provider $provider
    ) {
        $id = uniqid();

        $this->selectCollection->select()->insertOne([
            '_id' => $id,
            'user' => $user,
            'country' => $country->getIso(),
            'prefix' => $country->getPrefix(),
            'account' => $account,
            'type' => $type,
            'provider' => $provider->getId(),
            'name' => '',
            'deleted' => false
        ]);

        /** @var Contact $contact */
        $contact = $this->selectCollection->select()->findOne([
            '_id' => $id
        ]);

        foreach ($this->listeners as $listener) {
            $listener(
                $contact
            );
        }

        return $contact;
    }
}
