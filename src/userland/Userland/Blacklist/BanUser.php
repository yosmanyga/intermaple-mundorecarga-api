<?php

namespace Intermaple\Mundorecarga\Userland\Blacklist;

use Yosmy\Userland;

/**
 * @di\service()
 */
class BanUser
{
    /**
     * @var Userland\Blacklist\BanUser
     */
    private $banUser;

    /**
     * @param Userland\Blacklist\BanUser $banUser
     */
    public function __construct(Userland\Blacklist\BanUser $banUser)
    {
        $this->banUser = $banUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/blacklist/ban-user"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $user
     */
    public function ban(
        string $user
    ) {
        $this->banUser->ban($user, ['type' => 'hand']);
    }
}