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
class AddUser
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
     * @cli\resolution({command: "/add-user"})
     *
     * @param string $number
     */
    public function add($number)
    {
        try {
            $uniqueness = $this->createUniqueness->create(uniqid());
        } catch (ExistentException $e) {
            throw new \LogicException();
        }

        $this->insertProfiles->insert(
            $uniqueness->getId(),
            [],
            $number
        );
    }
}
