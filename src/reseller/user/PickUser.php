<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class PickUser
{
    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @param SelectUserCollection $selectUserCollection
     */
    public function __construct(
        SelectUserCollection $selectUserCollection
    ) {
        $this->selectUserCollection = $selectUserCollection;
    }

    /**
     * @param string $reseller
     *
     * @return User
     */
    public function pick(
        string $reseller
    ) {
        /** @var User $user */
        $user = $this->selectUserCollection->select()->findOne([
            '_id' => $reseller
        ]);

        if (!$user) {
            throw new \LogicException();
        }

        return $user;
    }
}