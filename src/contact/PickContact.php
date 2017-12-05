<?php

namespace Intermaple\Mundorecarga;

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
    )
    {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string $id
     * @param string $client
     *
     * @return Contact
     */
    public function pick(
        string $id,
        ?string $client
    ) {
        $criteria = [
            '_id' => $id,
        ];

        if ($client != null) {
            $criteria['user'] = $client;
        }

        /** @var Contact $contact */
        $contact = $this->selectCollection->select()->findOne($criteria);

        if (is_null($contact)) {
            throw new \LogicException();
        }

        return $contact;
    }
}
