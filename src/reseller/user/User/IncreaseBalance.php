<?php

namespace Intermaple\Mundorecarga\Reseller\User;

use Intermaple\Mundorecarga\Reseller;
use MongoDB\BSON\UTCDateTime;

/**
 * @di\service()
 */
class IncreaseBalance
{
    /**
     * @var Reseller\SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @var Reseller\SelectTransactionCollection
     */
    private $selectTransactionCollection;

    /**
     * @param Reseller\SelectUserCollection        $selectUserCollection
     * @param Reseller\SelectTransactionCollection $selectTransactionCollection
     */
    public function __construct(
        Reseller\SelectUserCollection $selectUserCollection,
        Reseller\SelectTransactionCollection $selectTransactionCollection
    ) {
        $this->selectUserCollection = $selectUserCollection;
        $this->selectTransactionCollection = $selectTransactionCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/user/increase-balance"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $user
     * @param float  $amount
     * @param string $reference
     */
    public function increase(
        string $user,
        float $amount,
        string $reference
    ) {
        $this->selectUserCollection->select()->updateOne(
            [
                '_id' => $user
            ],
            [
                '$inc' => [
                    'balance' => $amount
                ]
            ]
        );

        $this->selectTransactionCollection->select()->insertOne([
            '_id' => uniqid(),
            'user' => $user,
            'amount' => $amount,
            'reference' => $reference,
            'date' => new UTCDateTime(time() * 1000),
        ]);
    }
}