<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service({private: true})
 */
class FindContact
{
    /**
     * @var SelectContactCollection
     */
    private $selectCollection;

    /**
     * @param SelectContactCollection $selectCollection
     */
    public function __construct(
        SelectContactCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string    $prefix
     * @param string    $account
     * @param string    $client
     * @param bool|null $deleted
     * @return Contact
     *
     * @throws NonexistentContactException
     */
    public function find(
        string $prefix,
        string $account,
        string $client,
        bool $deleted = null
    ) {
        $criteria = [
            'prefix' => $prefix,
            'account' => $account,
            'user' => $client,
        ];

        if ($deleted !== null) {
            $criteria['deleted'] = $deleted;
        }

        /** @var Contact $contact */
        $contact = $this->selectCollection->select()->findOne($criteria);

        if (is_null($contact)) {
            throw new NonexistentContactException();
        }

        return $contact;
    }
}
