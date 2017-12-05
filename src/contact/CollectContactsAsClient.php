<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class CollectContactsAsClient
{
    /**
     * @var CollectContacts
     */
    private $collectContacts;

    /**
     * @param CollectContacts $collectContacts
     */
    public function __construct(
        CollectContacts $collectContacts
    ) {
        $this->collectContacts = $collectContacts;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-contacts-as-client"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     *
     * @return Contacts
     */
    public function collect(
        string $client
    ) {
        return $this->collectContacts->collect(null, [$client], false);
    }
}
