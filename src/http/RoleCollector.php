<?php

namespace Intermaple\Mundorecarga\Http;

use Intermaple\Mundorecarga\Userland\Privilege;
use Symsonte\Authorization\Role\Collector as BaseCollector;

/**
 * @di\service({
 *     private: true
 * })
 */
class RoleCollector implements BaseCollector
{
    /**
     * @var Privilege\PickUser
     */
    private $pickPrivilegeProfile;

    /**
     * @param Privilege\PickUser $pickPrivilegeProfile
     */
    function __construct(
        Privilege\PickUser $pickPrivilegeProfile
    ) {
        $this->pickPrivilegeProfile = $pickPrivilegeProfile;
    }

    /**
     * {@inheritdoc}
     */
    public function collect($user)
    {
        return $this->pickPrivilegeProfile
            ->pick($user)
            ->getRoles();
    }
}
