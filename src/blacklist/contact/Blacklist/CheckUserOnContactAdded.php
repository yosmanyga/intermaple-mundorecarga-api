<?php

namespace Intermaple\Mundorecarga\Blacklist;

use Intermaple\Mundorecarga;
use Yosmy\Recharge;
use Yosmy\Userland\Blacklist;

/**
 * @di\service({
 *     tags: ['contact_added']
 * })
 */
class CheckUserOnContactAdded
{
    /**
     * @var Blacklist\PickUser
     */
    private $pickUser;

    /**
     * @var BanContact
     */
    private $banContact;

    /**
     * @param Blacklist\PickUser $pickUser
     * @param BanContact $banContact
     */
    public function __construct(Blacklist\PickUser $pickUser, BanContact $banContact)
    {
        $this->pickUser = $pickUser;
        $this->banContact = $banContact;
    }

    /**
     * @param Mundorecarga\Contact $contact
     */
    public function ban(
        Mundorecarga\Contact $contact
    ) {
        try {
            // Is banned?
            $this->pickUser->pick($contact->getUser());

            $this->banContact->ban(
                $contact,
                [
                    'type' => 'user-banned',
                    'value' => $contact->getUser()
                ]
            );
        } catch (Blacklist\NonexistentUserException $e) {
        }
    }

    /**
     * @param Mundorecarga\Contact $contact
     */
    public function __invoke(
        Mundorecarga\Contact $contact
    ) {
        $this->ban($contact);
    }
}
