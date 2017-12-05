<?php

namespace Intermaple\Mundorecarga\Blacklist;

/**
 * @di\service()
 */
class PickContact
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
     * @param string $prefix,
     * @param string $account,
     *
     * @return Contact
     *
     * @throws NonexistentContactException
     */
    public function pick(
        string $prefix,
        string $account
    ) {
        /** @var Contact $contact */
        $contact = $this->selectCollection->select()->findOne([
            '_id' => sprintf("%s-%s", $prefix, $account)
        ]);

        if (is_null($contact)) {
            throw new NonexistentContactException();
        }

        return $contact;
    }
}
