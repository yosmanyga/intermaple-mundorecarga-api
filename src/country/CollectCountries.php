<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class CollectCountries
{
    /**
     * @var Recharge\Ding\CollectCountries
     */
    private $collectCountries;

    /**
     * @param Recharge\Ding\CollectCountries $collectCountries
     */
    public function __construct(Recharge\Ding\CollectCountries $collectCountries)
    {
        $this->collectCountries = $collectCountries;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-countries"})
     *
     * @param string[] $isos
     *
     * @return Countries
     */
    public function collect($isos = null)
    {
        $countries = new Countries(
            $this->collectCountries->collect($isos)->getIterator()
        );

        return $countries;
    }
}
