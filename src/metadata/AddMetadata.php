<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service({private: true})
 */
class AddMetadata
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
    )
    {
        $this->selectMetadataCollection = $selectMetadataCollection;
    }

    /**
     * @param string $id
     * @param string $description
     * @param string $value
     */
    public function add(
        string $id,
        string $description,
        string $value
    ) {
        $this->selectMetadataCollection->select()->insertOne([
            '_id' => $id,
            'description' => $description,
            'value' => $value
        ]);
    }
}
