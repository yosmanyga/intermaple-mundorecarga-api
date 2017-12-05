<?php

namespace Intermaple\Mundorecarga\Userland\Registration;

use Yosmy\Userland;

/**
 * @di\service()
 */
class ComputeUsers
{
    /**
     * @var Userland\Registration\ComputeUsers
     */
    private $computeUsers;

    /**
     * @param Userland\Registration\ComputeUsers $computeUsers
     */
    public function __construct(
        Userland\Registration\ComputeUsers $computeUsers
    ) {
        $this->computeUsers = $computeUsers;
    }

    /**
     * @http\resolution({method: "POST", path: "/userland/registration/compute-users"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param $from
     * @param $to
     * @param $group
     *
     * @return array
     */
    public function compute(
        $from,
        $to,
        $group
    ) {
        return $this->computeUsers->compute(
            $from,
            $to,
            $group
        );
    }
}
