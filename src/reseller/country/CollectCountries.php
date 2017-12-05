<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectCountries
{
    /**
     * @var SelectCountryCollection
     */
    private $selectCollection;

    /**
     * @param SelectCountryCollection $selectCollection
     */
    public function __construct(
        SelectCountryCollection $selectCollection
    )
    {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string $user
     *
     * @return Countries
     */
    public function collect(
        string $user
    ) {
        $cursor = $this->selectCollection->select()->find(
            [
                'user' => $user
            ]
        );

        return new Countries($cursor);
    }
}
