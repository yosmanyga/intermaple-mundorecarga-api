<?php

namespace Intermaple\Mundorecarga\Reseller\User;

use Intermaple\Mundorecarga\Reseller;
use MongoDB\BSON\UTCDateTime;
use MongoDB\UpdateResult;

/**
 * @di\service()
 */
class DecreaseBalance
{
    /**
     * @var Reseller\SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @param Reseller\SelectUserCollection $selectUserCollection
     */
    public function __construct(
        Reseller\SelectUserCollection $selectUserCollection
    ) {
        $this->selectUserCollection = $selectUserCollection;
    }

    /**
     * @param string $user
     * @param float  $amount
     *
     * @throws InsufficientBalanceException
     */
    public function decrease(
        string $user,
        float $amount
    ) {
        /** @var Reseller\User $user */
        $user = $this->selectUserCollection->select()->findOne([
            '_id' => $user
        ]);

        if ($user->getBalance() < $amount) {
            throw new InsufficientBalanceException();
        }

        /** @var UpdateResult $update */
        $update = $this->selectUserCollection->select()->updateOne(
            [
                '_id' => $user->getId()
            ],
            [
                '$inc' => [
                    'balance' => -1 * $amount
                ]
            ]
        );

        if ($update->getModifiedCount() == 0) {
            throw new \LogicException();
        }
    }
}