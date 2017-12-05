<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Unique;
use Yosmy\Privilege;
use Yosmy\Phone;
use Yosmy\Register;

/**
 * @di\service()
 */
class PurgeUsers
{
    /**
     * @var Unique\Uniqueness\PurgeCollection
     */
    private $purgeUniquenessCollection;

    /**
     * @var Privilege\Profile\PurgeCollection
     */
    private $purgePrivilegeProfileCollection;

    /**
     * @var Phone\Profile\PurgeCollection
     */
    private $purgePhoneProfileCollection;

    /**
     * @var Register\Profile\PurgeCollection
     */
    private $purgeRegisterProfileCollection;

    /**
     * @param Unique\Uniqueness\PurgeCollection $purgeUniquenessCollection
     * @param Privilege\Profile\PurgeCollection $purgePrivilegeProfileCollection
     * @param Phone\Profile\PurgeCollection $purgePhoneProfileCollection
     * @param Register\Profile\PurgeCollection $purgeRegisterProfileCollection
     */
    public function __construct(
        Unique\Uniqueness\PurgeCollection $purgeUniquenessCollection,
        Privilege\Profile\PurgeCollection $purgePrivilegeProfileCollection,
        Phone\Profile\PurgeCollection $purgePhoneProfileCollection,
        Register\Profile\PurgeCollection $purgeRegisterProfileCollection
    ) {
        $this->purgeUniquenessCollection = $purgeUniquenessCollection;
        $this->purgePrivilegeProfileCollection = $purgePrivilegeProfileCollection;
        $this->purgePhoneProfileCollection = $purgePhoneProfileCollection;
        $this->purgeRegisterProfileCollection = $purgeRegisterProfileCollection;
    }

    /**
     */
    public function purge()
    {
        $this->purgeUniquenessCollection->purge();
        $this->purgePrivilegeProfileCollection->purge();
        $this->purgePhoneProfileCollection->purge();
        $this->purgeRegisterProfileCollection->purge();
    }
}
