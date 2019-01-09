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
     * @param string $type
     *
     * @return Providers
     */
    public function find($country, $prefix, $account, $type)
    {
        $exclude = [];

        if ($type == "phone" && $prefix == '53') {
            $exclude = [
                '3CCU',
                'CBCU',
                'CACU'
            ];
        }
        
        $providers = new Providers(
            $this->findProviders->find($country, $prefix, $account, $type, $exclude)->getIterator()
        );

        return $providers;
    }
}
