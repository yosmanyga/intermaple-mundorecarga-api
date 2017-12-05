<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class CollectContactsAsOperator
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
     * @http\resolution({method: "POST", path: "/collect-contacts-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string[] $ids
     * @param string[] $users
     *
     * @return Contacts
     */
    public function collect(
        ?array $ids,
        ?array $users
    ) {
        return $this->collectContacts->collect($ids, $users, false);
    }
}
