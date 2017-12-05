<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class Populate
{
    /**
     * @var PurgeUsers;
     */
    private $purgeUsers;

    /**
     * @var PopulateUsers
     */
    private $populateUsers;

    /**
     * @var PurgeContacts
     */
    private $purgeContacts;

    /**
     * @var PopulateContacts
     */
    private $populateContacts;

    /**
     * @var PurgeTopups
     */
    private $purgeTopups;

    /**
     * @var PopulateTopups
     */
    private $populateTopups;

    /**
     * @param PurgeUsers $purgeUsers
     * @param PopulateUsers $populateUsers
     * @param PurgeContacts $purgeContacts
     * @param PopulateContacts $populateContacts
     * @param PurgeTopups $purgeTopups
     * @param PopulateTopups $populateTopups
     */
    public function __construct(
        PurgeUsers $purgeUsers,
        PopulateUsers $populateUsers,
        PurgeContacts $purgeContacts,
        PopulateContacts $populateContacts,
        PurgeTopups $purgeTopups,
        PopulateTopups $populateTopups
    ) {
        $this->purgeUsers = $purgeUsers;
        $this->populateUsers = $populateUsers;
        $this->purgeContacts = $purgeContacts;
        $this->populateContacts = $populateContacts;
        $this->purgeTopups = $purgeTopups;
        $this->populateTopups = $populateTopups;
    }

    /**
     * @cli\resolution({command: "/populate"})
     */
    public function populate()
    {
        $this->purgeUsers->purge();
        $this->populateUsers->populate(100);

        $this->purgeContacts->purge();
        $this->populateContacts->populate(50);

        $this->purgeTopups->purge();
        $this->populateTopups->populate(1000);
    }
}
