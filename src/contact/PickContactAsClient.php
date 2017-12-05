<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PickContactAsClient
{
    /**
     * @var PickContact
     */
    private $pickContact;

    /**
     * @param PickContact $pickContact
     */
    public function __construct(
        PickContact $pickContact
    ) {
        $this->pickContact = $pickContact;
    }

    /**
     * @http\resolution({method: "POST", path: "/pick-contact-as-client"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $id
     * @param string $client
     *
     * @return Contact
     */
    public function pick(
        string $id,
        string $client
    ) {
        return $this->pickContact->pick($id, $client);
    }
}
