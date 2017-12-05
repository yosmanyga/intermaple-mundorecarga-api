<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class LoadCountries
{
    /**
     * @var Recharge\Ding\LoadCountries
     */
    private $loadCountries;

    /**
     * @param Recharge\Ding\LoadCountries $loadCountries
     */
    public function __construct(Recharge\Ding\LoadCountries $loadCountries)
    {
        $this->loadCountries = $loadCountries;
    }

    /**
     * @cli\resolution({command: "/load-countries"})
     */
    public function load()
    {
        $favorites = [
            'AR', 'BO', 'CL', 'CO', 'CR', 'CU', 'DO', 'EC', 'SV', 'GT', 'HN', 'MX', 'NI', 'PA', 'PY', 'PE', 'PR', 'ES',
            'UY', 'VE'
        ];

        $this->loadCountries->load($favorites);
    }
}
