<?php

namespace Intermaple\Mundorecarga;

use Intermaple\Mundorecarga\Topup\Event;
use Yosmy\Stripe;
use Intermaple\Mundorecarga\Ding;
use Yosmy\Unique\CollectUniquenesses;
use Yosmy\Unique\CreateUniqueness;
use Yosmy\Unique\Uniqueness\ExistentException;

/**
 * @di\service()
 */
class PopulateUsers
{
    /**
     * @var CreateUniqueness
     */
    private $createUniqueness;

    /**
     * @var AddProfiles
     */
    private $insertProfiles;

    /**
     * @param CreateUniqueness $createUniqueness
     * @param AddProfiles $insertProfiles
     */
    public function __construct(
        CreateUniqueness $createUniqueness,
        AddProfiles $insertProfiles
    ) {
        $this->createUniqueness = $createUniqueness;
        $this->insertProfiles = $insertProfiles;
    }

    /**
     * @param int      $amount
     */
    public function populate($amount)
    {
        for ($i = 1; $i <= $amount; $i++) {
            try {
                $uniqueness = $this->createUniqueness->create(uniqid());
            } catch (ExistentException $e) {
                throw new \LogicException();
            }

            $this->insertProfiles->insert(
                $uniqueness->getId(),
                ['client'],
                sprintf('+1%s', rand(111111111, 999999999))
            );
        }
    }
}
