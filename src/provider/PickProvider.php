<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class PickProvider
{
    /**
     * @var Recharge\Ding\PickProvider
     */
    private $pickProvider;

    /**
     * @param Recharge\Ding\PickProvider $pickProvider
     */
    public function __construct(Recharge\Ding\PickProvider $pickProvider)
    {
        $this->pickProvider = $pickProvider;
    }

    /**
     * @http\resolution({method: "POST", path: "/pick-provider"})
     *
     * @param string|null $id
     * @param string|null $product
     *
     * @return Provider
     *
     * @throws NonexistentProviderException
     */
    public function pick($id = null, $product = null)
    {
        try {
            $provider = $this->pickProvider->pick($id, $product);
        } catch (Recharge\Ding\NonexistentProviderException $e) {
            throw new NonexistentProviderException();
        }

        return (new Provider($provider->getArrayCopy()));
    }
}
