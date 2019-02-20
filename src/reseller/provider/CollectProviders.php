<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectProviders
{
    /**
     * @var SelectProviderCollection
     */
    private $selectCollection;

    /**
     * @param SelectProviderCollection $selectCollection
     */
    public function __construct(
        SelectProviderCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-providers"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     *
     * @return Providers
     */
    public function collect(
        string $reseller
    ) {
        $cursor = $this->selectCollection->select()->find([
            'user' => $reseller
        ]);

        return new Providers($cursor);
    }
}