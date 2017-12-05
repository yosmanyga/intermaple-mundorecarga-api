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
     * @param string[] $ids
     *
     * @return Metadatas
     */
    public function collect($ids)
    {
        $criteria = [];

        if ($ids) {
            $criteria['_id'] = ['$in' => $ids];
        }

        $cursor = $this->selectMetadataCollection->select()->find(
            $criteria,
            [
                'sort' => [
                    'description' => 1
                ]
            ]
        );

        return new Metadatas($cursor);
    }
}
