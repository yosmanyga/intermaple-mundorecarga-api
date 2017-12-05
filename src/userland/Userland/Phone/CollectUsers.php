<?php

namespace Intermaple\Mundorecarga\Userland\Phone;

use Yosmy\Userland;

/**
 * @di\service()
 */
class CollectUsers
{
    /**
     * @var Userland\Phone\CollectUsers
     */
    private $collectUsers;

    /**
     * @param Userland\Phone\CollectUsers $collectUsers
     */
    public function __construct(
        Userland\Phone\CollectUsers $collectUsers
    ) {
        $this->collectUsers = $collectUsers;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/phone/collect-users"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string[] $ids
     *
     * @return Users
     */
    public function collect(
        array $ids
    ) {
        $countries = new Users(
            $this->collectUsers->collect($ids)->getIterator()
        );

        return $countries;
    }
}