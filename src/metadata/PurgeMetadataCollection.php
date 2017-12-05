<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PurgeMetadataCollection
{
    /**
     * @var SelectMetadataCollection
     */
    private $selectMetadataCollection;

    /**
     * @param SelectMetadataCollection $selectMetadataCollection
     */
    public function __construct(SelectMetadataCollection $selectMetadataCollection)
    {
        $this->selectMetadataCollection = $selectMetadataCollection;
    }

    public function purge()
    {
        $this->selectMetadataCollection->select()->drop();
    }
}
