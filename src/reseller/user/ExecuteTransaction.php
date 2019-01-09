<?php

namespace Intermaple\Mundorecarga\Reseller;

use Intermaple\Mundorecarga\Reseller;
use MongoDB\BSON\UTCDateTime;

/**
 * @di\service()
 */
class ExecuteTransaction
{
    /**
     * @var Reseller\User\IncreaseBalance
     */
    private $increaseBalance;

    /**
     * @var Reseller\SelectTransactionCollection
     */
    private $selectTransactionCollection;

    /**
     * @param User\IncreaseBalance        $increaseBalance
     * @param SelectTransactionCollection $selectTransactionCollection
     */
    public function __construct(
        User\IncreaseBalance $increaseBalance,
        SelectTransactionCollection $selectTransactionCollection
    ) {
        $this->increaseBalance = $increaseBalance;
        $this->selectTransactionCollection = $selectTransactionCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/execute-transaction"})
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
        $this->increaseBalance->increase(
            $user,
            $amount
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