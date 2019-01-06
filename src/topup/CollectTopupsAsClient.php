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
     * @var CollectTopups
     */
    private $collectTopups;

    /**
     * @param PickContact   $pickContact
     * @param CollectTopups $collectTopups
     */
    public function __construct(
        PickContact $pickContact,
        CollectTopups $collectTopups
    ) {
        $this->pickContact = $pickContact;
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
        $client,
        $contact
    ) {
        $contact = $this->pickContact->pick($contact, $client);

        $topups = $this->collectTopups->collect(
            [$contact->getId()],
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
