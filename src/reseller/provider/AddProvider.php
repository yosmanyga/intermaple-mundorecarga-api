<?php

namespace Intermaple\Mundorecarga\Reseller;

use Yosmy\Recharge;
use Yosmy\Userland;

/**
 * @di\service()
 */
class AddProvider
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var SelectProviderCollection
     */
    private $selectCollection;

    /**
     * @param Userland\Phone\PickUser $pickPhoneUser
     * @param SelectProviderCollection $selectCollection
     */
    public function __construct(
        Userland\Phone\PickUser $pickPhoneUser,
        SelectProviderCollection $selectCollection
    ) {
        $this->pickPhoneUser = $pickPhoneUser;
        $this->selectCollection = $selectCollection;
    }

    /**
     * @cli\resolution({command: "/reseller/user/add-provider"})
     * @http\resolution({method: "POST", path: "/reseller/user/add-provider"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $country
     * @param string $prefix
     * @param string $number
     * @param string $user
     * @param float  $discount
     */
    public function add(
        string $country,
        string $prefix,
        string $number,
        string $id,
        float $discount
    ) {
        try {
            $user = $this->pickPhoneUser->pick(null, $country, $prefix, $number)->getId();
        } catch (Userland\Phone\NonexistentUserException $e) {
            throw new \LogicException();
        }

        $this->selectCollection->select()->insertOne(
            [
                '_id' => uniqid(),
                'pid' => $id,
                'user' => $user,
                'discount' => $discount,
            ]
        );
    }
}
