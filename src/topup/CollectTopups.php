<?php

namespace Intermaple\Mundorecarga;

use MongoDB\BSON\UTCDateTime;
use Yosmy\Userland;

/**
 * @di\service()
 */
class CollectTopups
{
    /**
     * @var Userland\Phone\PickUser
     */
    private $pickPhoneUser;

    /**
     * @var CollectContactsAsOperator
     */
    private $collectContacts;

    /**
     * @var SelectTopupCollection
     */
    private $selectCollection;

    /**
     * @param Userland\Phone\PickUser   $pickPhoneUser
     * @param CollectContactsAsOperator $collectContacts
     * @param SelectTopupCollection     $selectCollection
     */
    public function __construct(
        Userland\Phone\PickUser $pickPhoneUser,
        CollectContactsAsOperator $collectContacts,
        SelectTopupCollection $selectCollection
    ) {
        $this->pickPhoneUser = $pickPhoneUser;
        $this->collectContacts = $collectContacts;
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param array    $contacts
     * @param string[] $steps
     * @param string   $phone
     * @param int      $from
     * @param int      $to
     * @param string   $stripe
     * @param int      $limit
     *
     * @return Topups
     */
    public function collect(
        ?array $contacts,
        ?array $steps,
        ?string $phone,
        ?int $from,
        ?int $to,
        ?string $stripe,
        ?int $limit
    ) {
        $criteria = $this->buildCriteria($contacts, $steps, $phone, $from, $to, $stripe);

        if (!$criteria) {
            return new Topups(new \ArrayIterator());
        }

        if ($limit === null) {
            $limit = 200;
        }

        $cursor = $this->selectCollection->select()->find(
            $criteria,
            [
                'limit' => $limit,
                'sort' => [
                    'date' => -1
                ],
            ]
        );

        return new Topups($cursor);
    }

    /**
     * @param array    $contacts
     * @param string[] $steps
     * @param string   $phone
     * @param int      $from
     * @param int      $to
     * @param string   $stripe
     *
     * @return array
     */
    private function buildCriteria(
        ?array $contacts,
        ?array $steps,
        ?string $phone,
        ?int $from,
        ?int $to,
        ?string $stripe
    ) {
        $criteria = [];

        if ($contacts !== null) {
            $criteria['contact'] = ['$in' => $contacts];
        }

        if ($steps !== null) {
            $criteria['steps'] = ['$in' => $steps];
        }

        if ($phone !== null) {
            try {
                [$prefix, $account] = explode('-', $phone);

                $user = $this->pickPhoneUser->pick(
                    null,
                    null,
                    $prefix,
                    $account
                );

                $contacts = $this->collectContacts->collect(
                    null,
                    [$user->getId()]
                );

                $ids = [];
                foreach ($contacts as $contact) {
                    $ids[] = $contact->getId();
                }

                $criteria['contact'] = ['$in' => $ids];
            } catch (Userland\Phone\NonexistentUserException $e) {
            }
        }

        if ($from !== null) {
            $criteria['date']['$gte'] = new UTCDateTime($from * 1000);
        }

        if ($to !== null) {
            $criteria['date']['$lt'] = new UTCDatetime($to * 1000);
        }

        if ($stripe !== null) {
            $criteria['stripe'] = $stripe;
        }

        return $criteria;
    }
}
