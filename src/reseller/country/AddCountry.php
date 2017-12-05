<?php

namespace Intermaple\Mundorecarga\Reseller;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class AddCountry
{
    /**
     * @var SelectCountryCollection
     */
    private $selectCollection;

    /**
     * @param SelectCountryCollection $selectCollection
     */
    public function __construct(
        SelectCountryCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @cli\resolution({command: "/reseller/add-country"})
     * @http\resolution({method: "POST", path: "/reseller/add-country"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $user
     * @param string $iso
     * @param float  $discount
     */
    public function add(
        string $user,
        string $iso,
        float $discount
    ) {
        $this->selectCollection->select()->insertOne([
            '_id' => uniqid(),
            'user' => $user,
            'iso' => $iso,
            'discount' => $discount,
        ]);
    }
}
