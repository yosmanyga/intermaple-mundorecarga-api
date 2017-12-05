<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class CollectContacts
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
     * @param string[] $ids
     * @param string[] $users
     * @param bool     $deleted
     *
     * @return Contacts
     */
    public function collect(
        ?array $ids,
        ?array $users,
        ?bool $deleted
    ) {
        $criteria = [];

        if ($ids !== null) {
            $criteria['_id'] = ['$in' => $ids];
        }

        if ($users !== null) {
            $criteria['user'] = ['$in' => $users];
        }

        if ($deleted !== null) {
            $criteria['deleted'] = $deleted;
        }

        $cursor = $this->selectCollection->select()->find($criteria);

        return new Contacts($cursor);
    }
}
