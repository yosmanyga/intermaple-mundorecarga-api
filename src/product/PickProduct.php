<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class PickProduct
{
    /**
     * @var Recharge\Ding\PickProduct
     */
    private $pickProduct;

    /**
     * @param Recharge\Ding\PickProduct $pickProduct
     */
    public function __construct(Recharge\Ding\PickProduct $pickProduct)
    {
        $this->pickProduct = $pickProduct;
    }

    /**
     * @http\resolution({method: "POST", path: "/pick-product"})
     *
     * @param string $id
     *
     * @return Product
     *
     * @throws NonexistentProductException
     */
    public function pick($id)
    {
        try {
            $product = $this->pickProduct->pick($id);
        } catch (Recharge\Ding\NonexistentProductException $e) {
            throw new NonexistentProductException();
        }

        return (new Product($product->getArrayCopy()));
    }
}
