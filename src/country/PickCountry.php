<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class PickCountry
{
    /**
     * @var Recharge\Ding\PickCountry
     */
    private $pickCountry;

    /**
     * @param Recharge\Ding\PickCountry $pickCountry
     */
    public function __construct(Recharge\Ding\PickCountry $pickCountry)
    {
        $this->pickCountry = $pickCountry;
    }

    /**
     * @http\resolution({method: "POST", path: "/pick-country"})
     *
     * @param string $iso
     *
     * @return Country
     *
     * @throws NonexistentCountryException
     */
    public function pick($iso)
    {
        try {
            $country = $this->pickCountry->pick($iso);
        } catch (Recharge\Ding\NonexistentCountryException $e) {
            throw new NonexistentCountryException();
        }

        return (new Country($country->getArrayCopy()));
    }
}
