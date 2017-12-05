<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class DetectProviders
{
    /**
     * @var Recharge\Ding\DetectProviders
     */
    private $detectProviders;

    /**
     * @param Recharge\Ding\DetectProviders $detectProviders
     */
    public function __construct(Recharge\Ding\DetectProviders $detectProviders)
    {
        $this->detectProviders = $detectProviders;
    }

    /**
     * @http\resolution({method: "POST", path: "/detect-providers"})
     *
     * @param string $prefix
     * @param string $account
     * 
     * @return Providers
     *
     * @throws InvalidAccountException
     */
    public function detect($prefix, $account)
    {
        try {
            $providers = new Providers(
                $this->detectProviders->detect($prefix, $account)->getIterator()
            );
        } catch (Recharge\Ding\AccountException $e) {
            throw new InvalidAccountException();
        }

        return $providers;
    }
}
