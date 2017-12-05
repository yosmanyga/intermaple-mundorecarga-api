<?php

namespace Intermaple\Mundorecarga\Country;

/**
 * @di\service()
 */
class CollectPhotos
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
     * @http\resolution({method: "POST", path: "/country/collect-photos"})
     *
     * @param string $country
     *
     * @return Photos
     */
    public function collect($country)
    {
        $cursor = $this->selectCollection->select()->find([
            'country' => $country
        ]);

        return new Photos($cursor);
    }
}
