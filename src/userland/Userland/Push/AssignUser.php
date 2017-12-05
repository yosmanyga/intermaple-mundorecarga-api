<?php

namespace Intermaple\Mundorecarga\Userland\Push;

use Yosmy\Userland;

/**
 * @di\service()
 */
class AssignUser
{
    /**
     * @var Userland\Push\AssignUser
     */
    private $assignUser;

    /**
     * @param Userland\Push\AssignUser $assignUser
     */
    public function __construct(
        Userland\Push\AssignUser $assignUser
    ) {
        $this->assignUser = $assignUser;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/push/assign-user"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param $client
     * @param $push
     */
    public function assign($client, $push)
    {
        $this->assignUser->assign($client, $push);
    }
}
