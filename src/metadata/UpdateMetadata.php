<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class UpdateMetadata
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
     * @http\resolution({method: "POST", path: "/update-metadata"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $id
     * @param string $value
     */
    public function update($id, $value)
    {
        $this->selectMetadataCollection->select()->updateOne(
            ['_id' => $id],
            ['$set' => [
                'value' => $value,
            ]]
        );
    }
}
