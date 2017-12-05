<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PickContactAsOperator
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
    )
    {
        $this->pickContact = $pickContact;
    }

    /**
     * @http\resolution({method: "POST", path: "/pick-contact-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id
     *
     * @return Contact
     */
    public function pick(
        string $id
    ) {
        return $this->pickContact->pick($id, null);
    }
}
