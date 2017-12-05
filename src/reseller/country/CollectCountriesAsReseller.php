<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectCountriesAsReseller
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
    ) {
        $this->collectCountries = $collectCountries;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-countries-as-reseller"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     *
     * @return Countries
     */
    public function collect(
        string $reseller
    ) {
        return $this->collectCountries->collect(
            $reseller
        );
    }
}
