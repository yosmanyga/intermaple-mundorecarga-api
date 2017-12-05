<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\BSON\UTCDateTime;
use Yosmy\Recharge;

/**
 * @di\service()
 */
class AddTransaction
{
    /**
     * @var SelectTransactionCollection
     */
    private $selectTransactionCollection;

    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @param SelectTransactionCollection $selectTransactionCollection
     * @param SelectUserCollection        $selectUserCollection
     */
    public function __construct(
        SelectTransactionCollection $selectTransactionCollection,
        SelectUserCollection $selectUserCollection
    ) {
        $this->selectTransactionCollection = $selectTransactionCollection;
        $this->selectUserCollection = $selectUserCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/add-transaction"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $user
     * @param float  $amount
     * @param string $reference
     */
    public function add(
        string $user,
        float $amount,
        string $reference
    ) {
        $this->selectTransactionCollection->select()->insertOne([
            '_id' => uniqid(),
            'user' => $user,
            'amount' => $amount,
            'reference' => $reference,
            'date' => new UTCDateTime(time() * 1000),
        ]);

        $this->selectUserCollection->select()->updateOne(
            [
                '_id' => $user
            ],
            [
                '$inc' => [
                    'amount' =>$amount
                ]
            ]
        );
    }
}
