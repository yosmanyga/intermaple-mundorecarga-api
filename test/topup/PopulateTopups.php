<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Privilege;

/**
 * @di\service()
 */
class PopulateTopups
{
    /**
     * @var Privilege\CollectProfiles
     */
    private $collectPrivilegeProfiles;

    /**
     * @var CollectContacts
     */
    private $collectContacts;

    /**
     * @var CollectProducts
     */
    private $collectProducts;

    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @param Privilege\CollectProfiles $collectPrivilegeProfiles
     * @param CollectContacts $collectContacts
     * @param CollectProducts $collectProducts
     * @param SelectTopupCollection $selectTopupCollection
     */
    public function __construct(
        Privilege\CollectProfiles $collectPrivilegeProfiles,
        CollectContacts $collectContacts,
        CollectProducts $collectProducts,
        SelectTopupCollection $selectTopupCollection
    ) {
        $this->collectPrivilegeProfiles = $collectPrivilegeProfiles;
        $this->collectContacts = $collectContacts;
        $this->collectProducts = $collectProducts;
        $this->selectTopupCollection = $selectTopupCollection;
    }

    /**
     * @param int $amount
     */
    public function populate($amount)
    {
        $profiles = iterator_to_array($this->collectPrivilegeProfiles->findHaving(['client']));

        $now = time();

        for ($i = 1; $i <= $amount; $i++) {
            $id = uniqid();

            /** @var Privilege\Profile $profile */
            $profile = $profiles[rand(0, count($profiles) - 1)];

            $contacts = iterator_to_array($this->collectContacts->collect($profile->getUniqueness()));

            if (!$contacts) {
                $i--;

                continue;
            }

            /** @var Contact $contact */
            $contact = $contacts[rand(0, count($contacts) - 1)];

            $products = iterator_to_array($this->collectProducts->collect($contact->getProvider()));
            /** @var Product $product */
            $product = $products[rand(0, count($products) - 1)];

            $statuses = [
                Topup::STATUS_FAILED_TRANSFER,
                Topup::STATUS_FAILED_VALIDATION,
                Topup::STATUS_FAILED_PAYMENT,
                Topup::STATUS_PROCESSING_TRANSFER,
                Topup::STATUS_REFUNDED_PAYMENT,
                Topup::STATUS_SUCCESSFUL_TRANSFER,
            ];
            $status = $statuses[rand(0, count($statuses) - 1)];

            $date = rand($now - 100000000, $now);

            $this->selectTopupCollection->select()->insertOne([
                $id,
                $contact->getId(),
                $product->getCode(),
                rand(5, 50),
                $date,
                1,
                $status
            ]);
        }
    }
}
