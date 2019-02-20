<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectTopupsAsAdmin
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
     * @http\resolution({method: "POST", path: "/reseller/collect-topups-as-admin"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param int   $from
     * @param int   $to
     * @param array $agents
     *
     * @return Topups
     */
    public function collect(
        int $from,
        int $to,
        ?array $agents
    ) {
        return $this->collectTopups->collect(
            $from,
            $to,
            $agents
        );
    }
}
