<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class CollectMetadatas
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
     * @http\resolution({method: "POST", path: "/collect-metadatas"})
     *
     * @return Metadatas
     */
    public function collect()
    {
        $cursor = $this->selectMetadataCollection->select()->find(
            [],
            [
                'sort' => [
                    'description' => 1
                ]
            ]
        );

        return new Metadatas($cursor);
    }
}
