<?php

namespace Intermaple\Mundorecarga\Country;

/**
 * @di\service()
 */
class DeletePhoto
{
    /**
     * @var SelectPhotoCollection
     */
    private $selectCollection;

    /**
     * @param SelectPhotoCollection $selectCollection
     */
    public function __construct(
        SelectPhotoCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/country/delete-photo"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $id
     */
    public function upload(
        string $id
    ) {
        $this->selectCollection->select()->deleteOne(['_id' => $id]);
    }
}
