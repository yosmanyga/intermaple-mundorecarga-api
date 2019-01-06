<?php

namespace Intermaple\Mundorecarga\UpgradeCollectionsTo20190107;

use Intermaple\Mundorecarga\SelectTopupCollection;
use Intermaple\Mundorecarga\Userland\Referral;

/**
 * @di\service()
 */
class UpgradeReferralCollection
{
    /**
     * @var Referral\SelectTopupCollection
     */
    private $selectReferralTopupCollection;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @param Referral\SelectTopupCollection $selectReferralTopupCollection
     * @param SelectTopupCollection $selectTopupCollection
     */
    public function __construct(
        Referral\SelectTopupCollection $selectReferralTopupCollection,
        SelectTopupCollection $selectTopupCollection
    ) {
        $this->selectReferralTopupCollection = $selectReferralTopupCollection;
        $this->selectTopupCollection = $selectTopupCollection;
    }

    public function upgrade()
    {
        $referralTopups = $this->selectReferralTopupCollection->select()->find([], [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array'
            ],
        ]);

        foreach ($referralTopups as $referralTopup) {
            $topup = $this->selectTopupCollection->select()->findOne([
                'ding' => $referralTopup['_id']
            ]);

            $this->selectReferralTopupCollection->select()->deleteOne([
                '_id' => $referralTopup['_id']
            ]);

            $this->selectReferralTopupCollection->select()->insertOne([
                '_id' => $topup['id'],
                'profit' => $referralTopup['profit']
            ]);
        }
    }
}