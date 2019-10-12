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
     * @param string $pid
     * @param string $user
     *
     * @return Provider
     */
    public function pick(
        string $pid,
        string $user
    ) {
        /** @var Provider $provider */
        $provider = $this->selectCollection->select()->findOne([
            'pid' => $pid,
            'user' => $user,
        ]);

        if (!$provider) {
            throw new \LogicException();
        }

        return $provider;
    }
}