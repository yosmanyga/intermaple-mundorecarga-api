<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

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
     * @http\resolution({method: "POST", path: "/userland/referral/pick-user"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     *
     * @return User
     *
     * @throws NonexistentUserException
     */
    public function pick(
        string $client
    ) {
        /** @var User $user */
        $user = $this->selectUserCollection->select()->findOne([
            '_id' => $client
        ]);

        if (!$user) {
            throw new NonexistentUserException();
        }

        return $user;
    }
}