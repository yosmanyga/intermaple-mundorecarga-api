<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Userland;

/**
 * @di\service()
 */
class RefundTopup
{
    /**
     * @var PickTopup
     */
    private $pickTopup;

    /**
     * @var Userland\Stripe\RefundPayment
     */
    private $refundPayment;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @param PickTopup $pickTopup
     * @param Userland\Stripe\RefundPayment $refundPayment
     * @param SelectTopupCollection $selectTopupCollection
     */
    public function __construct(
        PickTopup $pickTopup,
        Userland\Stripe\RefundPayment $refundPayment,
        SelectTopupCollection $selectTopupCollection
    ) {
        $this->pickTopup = $pickTopup;
        $this->refundPayment = $refundPayment;
        $this->selectTopupCollection = $selectTopupCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/refund-topup"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id
     *
     * @return Topup
     */
    public function refund($id)
    {
        $topup = $this->pickTopup->pick($id);

        if (in_array(Topup::STEP_REFUND, $topup->getSteps())) {
            throw new \LogicException();
        }

        $this->refundPayment->refund($topup->getStripe());

        $this->selectTopupCollection->select()->updateOne(
            ['_id' => $topup->getId()],
            [
                '$push' => ['steps' => Topup::STEP_REFUND]
            ]
        );

        /* Return topup with updated steps field */

        /** @var Topup $topup */
        $topup = $this->selectTopupCollection
            ->select()
            ->findOne(
                ['_id' => $id]
            );

        return $topup;
    }
}