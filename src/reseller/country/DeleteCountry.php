<?php

namespace Intermaple\Mundorecarga\Reseller;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class DeleteCountry
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
     * @http\resolution({method: "POST", path: "/reseller/delete-country"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $id
     */
    public function delete(
        string $id
    ) {
        $this->selectCollection->select()->deleteOne([
            '_id' => $id,
        ]);
    }
}
