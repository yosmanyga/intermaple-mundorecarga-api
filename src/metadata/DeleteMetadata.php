<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class DeleteMetadata
{
    /**
     * @var SelectMetadataCollection
     */
    private $selectMetadataCollection;

    /**
     * @param SelectMetadataCollection $selectMetadataCollection
     */
    public function __construct(
        SelectMetadataCollection $selectMetadataCollection
    ) {
        $this->selectMetadataCollection = $selectMetadataCollection;
    }

    /**
     * @param string $id
     */
    public function delete($id)
    {
        $this->selectMetadataCollection->select()->deleteOne(
            ['_id' => $id]
        );
    }
}
