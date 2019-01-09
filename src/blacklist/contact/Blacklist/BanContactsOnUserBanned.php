<?php

namespace Intermaple\Mundorecarga\Blacklist;

use Intermaple\Mundorecarga\CollectContacts;
use Yosmy\Recharge;

/**
 * @di\service({
 *     tags: ['yosmy.userland.blacklist.user_banned']
 * })
 */
class BanContactsOnUserBanned
{
    /**
     * @var CollectContacts
     */
    private $collectContacts;

    /**
     * @var BanContact
     */
    private $banContact;

    /**
     * @param CollectContacts $collectContacts
     * @param BanContact      $banContact
     */
    public function __construct(
        CollectContacts $collectContacts,
        BanContact $banContact
    ) {
        $this->collectContacts = $collectContacts;
        $this->banContact = $banContact;
    }

    /**
     * @param string $user
     */
    public function ban(
        string $user
    ) {
        $contacts = $this->collectContacts->collect(null, [$user], null);

        foreach ($contacts as $contact) {
            $this->banContact->ban(
                $contact,
                [
                    'type' => 'user-banned',
                    'value' => $user
                ]
            );
        }
    }

    /**
     * @param string $user
     */
    public function __invoke(
        string $user
    ) {
        $this->ban($user);
    }
}
