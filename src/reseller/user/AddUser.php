<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class AddUser
{
    /**
     * @var SelectUserCollection
     */
    private $selectCollection;

    /**
     * @param SelectUserCollection $selectCollection
     */
    public function __construct(
        SelectUserCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string $id
     * @param string $name
     * @param float  $balance
     *
     * @return string
     */
    public function add(
        string $id,
        string $name,
        float $balance
    ) {
        $this->selectCollection->select()->insertOne([
            '_id' => $id,
            'name' => $name,
            'balance' => $balance,
        ]);

        return $id;
    }
}