<?php

namespace Intermaple\Mundorecarga\Country;

/**
 * @di\service()
 */
class PickPhoto
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
     * @http\resolution({method: "POST", path: "/country/pick-photo"})
     * @cache({"expiry": "1 day"})
     *
     * @param string $country
     *
     * @return Photo
     *
     * @throws NonexistentPhotoException
     */
    public function pick(
        string $country
    ) {
        /** @var \Traversable $photos */
        $photos = $this->selectCollection->select()->aggregate([
            ['$match' => ['country' => $country]],
            ['$sample' => ['size' => 1]]]
        );

        $photos = iterator_to_array($photos);

        if (!$photos) {
            throw new NonexistentPhotoException();
        }

        return $photos[0];
    }
}
