<?php

namespace Intermaple\Mundorecarga\Country;

/**
 * @di\service()
 */
class PurgePhotos
{
    /**
     * @var SelectPhotoCollection
     */
    private $selectPhotoCollection;

    /**
     * @param SelectPhotoCollection $selectPhotoCollection
     */
    public function __construct(SelectPhotoCollection $selectPhotoCollection)
    {
        $this->selectPhotoCollection = $selectPhotoCollection;
    }

    public function purge()
    {
        $this->selectPhotoCollection->select()->drop();
    }
}
