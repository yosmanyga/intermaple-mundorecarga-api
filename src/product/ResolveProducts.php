<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class ResolveProducts
{
    /**
     * @var Recharge\Ding\ResolveProducts
     */
    private $resolveProducts;

    /**
     * @param Recharge\Ding\ResolveProducts $resolveProducts
     */
    public function __construct(Recharge\Ding\ResolveProducts $resolveProducts)
    {
        $this->resolveProducts = $resolveProducts;
    }

    /**
     * @http\resolution({method: "POST", path: "/resolve-products"})
     *
     * @param string $provider
     *
     * @return Products
     */
    public function resolve($provider)
    {
        $products = new Products(
            $this->resolveProducts->resolve($provider)->getIterator()
        );

        return $products;
    }
}
