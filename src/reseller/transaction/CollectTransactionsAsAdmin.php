<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectTransactionsAsAdmin
{
    /**
     * @var CollectTransactions
     */
    private $collectTransactions;

    /**
     * @param CollectTransactions $collectTransactions
     */
    public function __construct(
        CollectTransactions $collectTransactions
    )
    {
        $this->collectTransactions = $collectTransactions;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-transactions-as-admin"})
     * @domain\authorization({roles: ["admin"]})
     *
     * @param int $from
     * @param int $to
     * @param int $limit
     *
     * @return Transactions
     */
    public function collect(
        ?int $from,
        ?int $to,
        ?int $limit
    ) {
        return $this->collectTransactions->collect(
            null,
            $from,
            $to,
            $limit
        );
    }
}
