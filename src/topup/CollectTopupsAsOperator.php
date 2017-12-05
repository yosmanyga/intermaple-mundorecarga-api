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
     * @http\resolution({method: "POST", path: "/collect-topups-as-operator"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string[] $contacts
     * @param int $from
     * @param int $to
     *
     * @return Topups
     */
    public function collect(
        ?array $contacts,
        ?int $from,
        ?int $to
    ) {
        return $this->collectTopups->collect(
            $contacts,
            null,
            $from,
            $to,
            null
        );
    }
}
