<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service({private: true})
 */
class ResolveContact
{
    /**
     * @var FindContact
     */
    private $findContact;

    /**
     * @var AddContact
     */
    private $addContact;

    /**
     * @var MoveContact
     */
    private $moveContact;

    /**
     * @param FindContact $findContact
     * @param AddContact $addContact
     * @param MoveContact $moveContact
     */
    public function __construct(
        FindContact $findContact,
        AddContact $addContact,
        MoveContact $moveContact
    ) {
        $this->findContact = $findContact;
        $this->addContact = $addContact;
        $this->moveContact = $moveContact;
    }

    /**
     * @param string   $client
     * @param Country  $country
     * @param string   $account
     * @param string   $type
     * @param Provider $provider
     *
     * @return Contact
     */
    public function resolve(
        string $client,
        Country $country,
        string $account,
        string $type,
        Provider $provider
    ) {
        try {
            $contact = $this->findContact->find(
                $country->getPrefix(),
                $account,
                $client
            );

            // Did the user change the contact provider?
            if ($contact->getProvider() != $provider->getId()) {
                $this->moveContact->move($contact, $provider);
            }
        } catch (NonexistentContactException $e) {
            $contact = $this->addContact->add(
                $client,
                $country,
                $account,
                $type,
                $provider
            );
        }

        return $contact;
    }
}
