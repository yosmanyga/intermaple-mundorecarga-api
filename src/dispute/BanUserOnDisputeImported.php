<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Userland\Blacklist;

/**
 * @di\service({
 *     tags: ['yosmy.userland.stripe.dispute_imported']
 * })
 */
class BanUserOnDisputeImported
{
    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @var PickContact
     */
    private $pickContact;

    /**
     * @var Blacklist\BanUser
     */
    private $banUser;

    /**
     * @param SelectTopupCollection $selectTopupCollection
     * @param PickContact           $pickContact
     * @param Blacklist\BanUser     $banUser
     */
    public function __construct(
        SelectTopupCollection $selectTopupCollection,
        PickContact $pickContact,
        Blacklist\BanUser $banUser
    ) {
        $this->selectTopupCollection = $selectTopupCollection;
        $this->pickContact = $pickContact;
        $this->banUser = $banUser;
    }

    /**
     * @param string $id
     * @param string $charge
     */
    public function ban(
        string $id,
        string $charge
    ) {
        /** @var Topup $topup */
        $topup = $this->selectTopupCollection->select()->findOne([
            'stripe' => $charge
        ]);

        $contact = $this->pickContact->pick(
            $topup->getContact(),
            null
        );

        $this->banUser->ban(
            $contact->getUser(),
            [
                'type' => 'dispute-imported',
                'value' => $id
            ]
        );
    }

    /**
     * @param string $id
     * @param string $charge
     */
    public function __invoke(
        string $id,
        string $charge
    ) {
        $this->ban($id, $charge);
    }
}