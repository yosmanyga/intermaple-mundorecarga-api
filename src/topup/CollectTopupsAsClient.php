<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class CollectTopupsAsClient
{
    /**
     * @var PickContact
     */
    private $pickContact;

    /**
     * @var CollectContactsAsClient
     */
    private $collectContacts;

    /**
     * @var CollectTopups
     */
    private $collectTopups;

    /**
     * @param PickContact             $pickContact
     * @param CollectContactsAsClient $collectContacts
     * @param CollectTopups           $collectTopups
     */
    public function __construct(
        PickContact $pickContact,
        CollectContactsAsClient $collectContacts,
        CollectTopups $collectTopups
    ) {
        $this->pickContact = $pickContact;
        $this->collectContacts = $collectContacts;
        $this->collectTopups = $collectTopups;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-topups-as-client"})
     * @domain\authorization({roles: ["client"]})
     *
     * @param string $client
     * @param string $contact
     *
     * @return TopupAsClient[]
     */
    public function collect(
        string $client,
        ?string $contact
    ) {
        if ($contact !== null) {
            $contact = $this->pickContact->pick($contact, $client);

            $contacts = [$contact->getId()];
        } else {
            $contacts = [];
            foreach ($this->collectContacts->collect($client) as $contact) {
                $contacts[] = $contact->getId();
            }
        }

        $topups = $this->collectTopups->collect(
            $contacts,
            null,
            null,
            null,
            null,
            null,
            200
        );

        $topupsAsClient = [];
        foreach ($topups as $topup) {
            $topupsAsClient[] = new TopupAsClient(
                $topup->getId(),
                $topup->getSteps(),
                $topup->getContact(),
                $topup->getProduct(),
                $topup->getAmount(),
                $topup->getDate()
            );
        }

        return $topupsAsClient;
    }
}
