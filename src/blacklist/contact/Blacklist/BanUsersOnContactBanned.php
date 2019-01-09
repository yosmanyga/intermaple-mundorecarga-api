<?php

namespace Intermaple\Mundorecarga\Blacklist;

use Intermaple\Mundorecarga\CollectContacts;
use Intermaple\Mundorecarga\PickContact;
use Yosmy\Recharge;
use Yosmy\Userland\Blacklist\BanUser;

/**
 * @di\service({
 *     tags: ['blacklist.contact_banned']
 * })
 */
class BanUsersOnContactBanned
{
    /**
     * @var PickContact
     */
    private $pickContact;

    /**
     * @var CollectContacts
     */
    private $collectContacts;

    /**
     * @var BanUser
     */
    private $banUser;

    /**
     * @param PickContact     $pickContact
     * @param CollectContacts $collectContacts
     * @param BanUser         $banUser
     */
    public function __construct(
        PickContact $pickContact,
        CollectContacts $collectContacts,
        BanUser $banUser
    ) {
        $this->pickContact = $pickContact;
        $this->collectContacts = $collectContacts;
        $this->banUser = $banUser;
    }

    /**
     * @param string $contact
     */
    public function ban(
        string $contact
    ) {
        $contact = $this->pickContact->pick($contact, null);

        $contacts = $this->collectContacts->collectByNumber($contact->getPrefix(), $contact->getAccount());

        foreach ($contacts as $contact) {
            $this->banUser->ban(
                $contact->getUser(),
                [
                    'type' => 'contact-banned',
                    'value' => $contact->getId()
                ]
            );
        }
    }

    /**
     * @param string $contact
     */
    public function __invoke(
        string $contact
    ) {
        $this->ban($contact);
    }
}
