<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Recharge;
use Yosmy\Log;

/**
 * @di\service()
 */
class TestTopup
{
    /**
     * @var PickProvider
     */
    private $pickProvider;

    /**
     * @var Recharge\Ding\SendTransfer
     */
    private $sendTransfer;

    /**
     * @var Log\AddEvent
     */
    private $addEvent;

    /**
     * @param PickProvider               $pickProvider
     * @param Recharge\Ding\SendTransfer $sendTransfer
     * @param Log\AddEvent               $addEvent
     */
    public function __construct(
        PickProvider $pickProvider,
        Recharge\Ding\SendTransfer $sendTransfer,
        Log\AddEvent $addEvent
    ) {
        $this->pickProvider = $pickProvider;
        $this->sendTransfer = $sendTransfer;
        $this->addEvent = $addEvent;
    }

    /**
     * @http\resolution({method: "POST", path: "/test-topup"})
     *
     * @param string $prefix
     * @param string $account
     * @param string $provider
     *
     * @throws Topup\AccountException
     * @throws Topup\ProviderException
     */
    public function test(
        $prefix,
        $account,
        $provider
    ) {
        try {
            $provider = $this->pickProvider->pick($provider);
        } catch (NonexistentProviderException $e) {
            throw new \LogicException(null, null, $e);
        }

        /* Test with the minimum amount */

        $firstProduct = $provider->getProducts()[0];

        try {
            $this->sendTransfer->test(
                'test-id',
                $prefix,
                $account,
                $firstProduct->getId(),
                $firstProduct->getMin()->getAmount()
            );
        } catch (Recharge\Ding\AccountException $e) {
            throw new Topup\AccountException();
        } catch (Recharge\Ding\ProviderException $e) {
            throw new Topup\ProviderException();
        }
    }
}
