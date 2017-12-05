<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PickMetadata
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
     * @http\resolution({method: "POST", path: "/pick-metadata"})
     *
     * @param string $id
     *
     * @return Metadata
     *
     * @throws NonexistentMetadataException
     */
    public function pick($id)
    {
        /** @var Metadata $metadata */
        $metadata = $this->selectMetadataCollection->select()->findOne([
            '_id' => $id,
        ]);

        if (is_null($metadata)) {
            throw new NonexistentMetadataException();
        }

        return $metadata;
    }
}
