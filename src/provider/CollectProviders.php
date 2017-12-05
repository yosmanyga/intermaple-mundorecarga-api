<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class CollectProviders
{
    /**
     * @var Recharge\Ding\CollectProviders
     */
    private $collectProviders;

    /**
     * @param Recharge\Ding\CollectProviders $collectProviders
     */
    public function __construct(Recharge\Ding\CollectProviders $collectProviders)
    {
        $this->collectProviders = $collectProviders;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-providers"})
     *
     * @param string|null $country
     *
     * @return Providers
     */
    public function collect($country = null)
    {
        $providers = new Providers(
            $this->collectProviders->collect(null, $country)->getIterator()
        );

        return $providers;
    }
}
