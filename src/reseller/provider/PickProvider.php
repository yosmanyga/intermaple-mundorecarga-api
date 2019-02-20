<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class PickProvider
{
    /**
     * @var SelectProviderCollection
     */
    private $selectCollection;

    /**
     * @param SelectProviderCollection $selectCollection
     */
    public function __construct(
        SelectProviderCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string $id
     *
     * @return Provider
     */
    public function pick(
        string $id
    ) {
        /** @var Provider $provider */
        $provider = $this->selectCollection->select()->findOne([
            'pid' => $id
        ]);

        if (!$provider) {
            throw new \LogicException();
        }

        return $provider;
    }
}