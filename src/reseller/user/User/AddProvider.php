<?php

namespace Intermaple\Mundorecarga\Reseller\User;

use Intermaple\Mundorecarga\Reseller\SelectUserCollection;
use Yosmy\Recharge;

/**
 * @di\service()
 */
class AddProvider
{
    /**
     * @var SelectUserCollection
     */
    private $selectCollection;

    /**
     * @param SelectUserCollection $selectCollection
     */
    public function __construct(
        SelectUserCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @cli\resolution({command: "/reseller/user/add-provider"})
     * @http\resolution({method: "POST", path: "/reseller/user/add-provider"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $id
     * @param string $user
     * @param float  $discount
     */
    public function add(
        string $user,
        string $id,
        float $discount
    ) {
        $this->selectCollection->select()->updateOne(
            [
                '_id' => $user
            ],
            [
                '$addToSet' => [
                    'providers' => [
                        'id' => $id,
                        'discount' => $discount
                    ]
                ]
            ]
        );
    }
}
