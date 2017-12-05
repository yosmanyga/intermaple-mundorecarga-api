<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectCountriesAsAdmin
{
    /**
     * @var CollectCountries
     */
    private $collectCountries;

    /**
     * @param CollectCountries $collectCountries
     */
    public function __construct(
        CollectCountries $collectCountries
    )
    {
        $this->collectCountries = $collectCountries;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-countries-as-admin"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $user
     *
     * @return Countries
     */
    public function collect(
        string $user
    ) {
        return $this->collectCountries->collect(
            $user
        );
    }
}
