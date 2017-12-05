<?php

namespace Intermaple\Mundorecarga\Country;

use GuzzleHttp\Exception\GuzzleException;

/**
 * @di\service()
 */
class UploadPhoto
{
    /**
     * @var ReducePhoto
     */
    private $reducePhoto;

    /**
     * @var SelectPhotoCollection
     */
    private $selectCollection;

    /**
     * @param ReducePhoto           $reducePhoto
     * @param SelectPhotoCollection $selectCollection
     */
    public function __construct(
        ReducePhoto $reducePhoto,
        SelectPhotoCollection $selectCollection
    ) {
        $this->reducePhoto = $reducePhoto;
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/country/upload-photo"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param string $country
     * @param string $data
     */
    public function upload(
        string $country,
        string $data
    ) {
        try {
            $normal = $this->reducePhoto->reduce(
                $data,
                800
            );

            $small = $this->reducePhoto->reduce(
                $data,
                100
            );
        } catch (GuzzleException $e) {
            throw new \LogicException(null, null, $e);
        }

        $this->selectCollection->select()->insertOne([
            '_id' => uniqid(),
            'country' => $country,
            'original' => $data,
            'normal' => $normal,
            'small' => $small
        ]);
    }
}
