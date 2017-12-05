<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class CollectProducts
{
    /**
     * @var Recharge\Ding\CollectProducts
     */
    private $collectProducts;

    /**
     * @param Recharge\Ding\CollectProducts $collectProducts
     */
    public function __construct(Recharge\Ding\CollectProducts $collectProducts)
    {
        $this->collectProducts = $collectProducts;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-products"})
     *
     * @param string|null $provider
     *
     * @return Products
     */
    public function collect($provider = null)
    {
        $products = new Products(
            $this->collectProducts->collect($provider)->getIterator()
        );

        return $products;
    }
}
