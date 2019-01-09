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
     * @cache({"expiry": "1 month"})
     *
     * @param string[] $ids
     * @param string   $country
     *
     * @return Providers
     */
    public function collect(
        $ids,
        $country
    ) {
        $providers = new Providers(
            $this->collectProviders->collect($ids, $country)->getIterator()
        );

        return $providers;
    }
}
