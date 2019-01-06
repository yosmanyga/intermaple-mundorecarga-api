<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class CollectTopupsAsOperator
{
    /**
     * @var CollectTopups
     */
    private $collectTopups;

    /**
     * @param CollectTopups $collectTopups
     */
    public function __construct(
        CollectTopups $collectTopups
    ) {
        $this->collectTopups = $collectTopups;
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-topups-by-contacts-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string[] $contacts
     *
     * @return Topups
     */
    public function collectByContacts(
        array $contacts
    ) {
        return $this->collectTopups->collect(
            $contacts,
            null,
            null,
            null,
            null,
            null,
            null
        );
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-topups-by-date-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param int $from
     * @param int $to
     *
     * @return Topups
     */
    public function collectByDate(
        ?int $from,
        ?int $to
    ) {
        return $this->collectTopups->collect(
            null,
            null,
            null,
            $from,
            $to,
            null,
            null
        );
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-topups-by-phone-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $phone
     *
     * @return Topups
     */
    public function collectByPhone(
        string $phone
    ) {
        $phone = str_replace(['+', ' '], '', $phone);

        return $this->collectTopups->collect(
            null,
            null,
            $phone,
            null,
            null,
            null,
            null
        );
    }

    /**
     * @http\resolution({method: "POST", path: "/collect-topups-by-stripe-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $stripe
     *
     * @return Topups
     */
    public function collectByStripe(
        string $stripe
    ) {
        return $this->collectTopups->collect(
            null,
            null,
            null,
            null,
            null,
            $stripe,
            null
        );
    }
}
