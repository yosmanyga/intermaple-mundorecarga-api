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
     */
    public function increase(
        string $user,
        float $amount
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
    }
}