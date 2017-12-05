<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PurgeTopups
{
    /**
     * @var SelectTopupCollection
     */
    private $selectTopupCollection;

    /**
     * @param SelectTopupCollection $selectTopupCollection
     */
    public function __construct(SelectTopupCollection $selectTopupCollection)
    {
        $this->selectTopupCollection = $selectTopupCollection;
    }

    public function purge()
    {
        $this->selectTopupCollection->select()->drop();
    }
}
