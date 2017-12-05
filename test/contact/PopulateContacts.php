<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Uniqueness;

/**
 * @di\service()
 */
class PopulateContacts
{
    /**
     * @var Unique\CollectUniquenesses
     */
    private $collectUniquenesses;

    /**
     * @var CollectCountries
     */
    private $collectCountries;

    /**
     * @var FindProviders
     */
    private $findProviders;

    /**
     * @var AddContact
     */
    private $addContact;

    /**
     * @param Unique\CollectUniquenesses $collectUniquenesses
     * @param CollectCountries $collectCountries
     * @param FindProviders $findProviders
     * @param AddContact $addContact
     */
    public function __construct(
        Unique\CollectUniquenesses $collectUniquenesses,
        CollectCountries $collectCountries,
        FindProviders $findProviders,
        AddContact $addContact
    ) {
        $this->collectUniquenesses = $collectUniquenesses;
        $this->collectCountries = $collectCountries;
        $this->findProviders = $findProviders;
        $this->addContact = $addContact;
    }

    /**
     * @param int $amount
     */
    public function populate($amount)
    {
        $uniquenesses = iterator_to_array($this->collectUniquenesses->collect());
        $countries = iterator_to_array($this->collectCountries->collect());

        for ($i = 1; $i <= $amount; $i++) {
            /** @var Unique\Uniqueness $uniqueness */
            $uniqueness = $uniquenesses[rand(0, count($uniquenesses) - 1)];

            do {
                /** @var Country $country */
                $country = $countries[rand(0, count($countries) - 1)];

                $account = rand(11111111, 99999999);

                try {
                    $providers = $this->findProviders->find(
                        $country->getIso(),
                        $country->getPrefix(),
                        $account
                    );
                } catch (InvalidAccountException $e) {
                    continue;
                }
            } while (!$providers);

            /** @var Provider $provider */
            $provider = $providers[rand(0, count($providers) - 1)];

            $this->addContact->add(
                $uniqueness->getId(),
                $country,
                $account,
                $provider
            );
        }
    }
}
