<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class FindProviders
{
    /**
     * @var Recharge\Ding\FindProviders
     */
    private $findProviders;

    /**
     * @param Recharge\Ding\FindProviders $findProviders
     */
    public function __construct(Recharge\Ding\FindProviders $findProviders)
    {
        $this->findProviders = $findProviders;
    }

    /**
     * @http\resolution({method: "POST", path: "/find-providers"})
     *
     * @param string $country
     * @param string $prefix
     * @param string $account
     * 
     * @return Providers
     */
    public function find($country, $prefix, $account)
    {
        $providers = new Providers(
            $this->findProviders->find($country, $prefix, $account)->getIterator()
        );

        return $providers;
    }
}
