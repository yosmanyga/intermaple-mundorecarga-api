<?php

namespace Intermaple\Mundorecarga\Userland\Referral\User;

use Intermaple\Mundorecarga\Userland\Referral\SelectUserCollection;

/**
 * @di\service()
 */
class AddReferral
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
     * @http\resolution({method: "POST", path: "/userland/referral/user/add-referral"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $code
     */
    public function add(
        string $client,
        string $code
    ) {
        /* Ensure that the client has not referred yet */

        $user = $this->selectUserCollection->select()->findOne([
            'referrals' => $client
        ]);

        if ($user) {
            return;
        }

        /* Add client to referrals */

        $this->selectUserCollection->select()->updateOne(
            [
                'code' => $code
            ],
            [
                '$addToSet' => [
                    'referrals' => $client
                ],
            ]
        );
    }
}