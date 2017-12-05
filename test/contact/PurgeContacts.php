<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PurgeContacts
{
    /**
     * @var Contact\PurgeContacts
     */
    private $purgeCollection;

    /**
     * @param Contact\PurgeContacts $purgeCollection
     */
    public function __construct(
        Contact\PurgeContacts $purgeCollection
    ) {
        $this->purgeCollection = $purgeCollection;
    }

    /**
     */
    public function purge()
    {
        $this->purgeCollection->purge();
    }
}
