<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

use Intermaple\Mundorecarga;

/**
 * @di\service({
 *     tags: ['topup_sent']
 * })
 */
class AddTopup
{
    /**
     * @var Mundorecarga\PickTopup
     */
    private $pickTopup;

    /**
     * @var Mundorecarga\PickContact
     */
    private $pickContact;

    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;
    
    /**
     * @var float
     */
    private $profit;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @di\arguments({
     *     profit: "%referral_profit%"
     * })
     *
     * @param Mundorecarga\PickTopup $pickTopup
     * @param Mundorecarga\PickContact $pickContact
     * @param SelectUserCollection   $selectUserCollection
     * @param float                  $profit
     * @param SelectTopupCollection  $selectTopupCollection
     */
    public function __construct(
        Mundorecarga\PickTopup $pickTopup,
        Mundorecarga\PickContact $pickContact,
        SelectUserCollection $selectUserCollection,
        float $profit,
        SelectTopupCollection $selectTopupCollection
    ) {
        $this->pickTopup = $pickTopup;
        $this->pickContact = $pickContact;
        $this->selectUserCollection = $selectUserCollection;
        $this->profit = $profit;
        $this->selectTopupCollection = $selectTopupCollection;
    }

    /**
     * @param string $id
     */
    public function add(
        string $id
    ) {
        $topup = $this->pickTopup->pick($id);

        $contact = $this->pickContact->pick($topup->getContact(), null);

        $user = $this->selectUserCollection->select()->findOne([
            'referral' => $contact->getUser()
        ]);
        
        // Is not the user a referral?
        if (!$user) {
            return;
        }
        
        // Profit
        $profit = $topup->getProfit() * $this->profit / 100;

        $this->selectTopupCollection->select()->insertOne([
            '_id' => $id,
            'profit' => $profit
        ]);
    }

    /**
     * @param string $id
     */
    public function __invoke(
        string $id
    ) {
        $this->add($id);
    }
}