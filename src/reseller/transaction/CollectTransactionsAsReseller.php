<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectTransactionsAsReseller
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
     * @http\resolution({method: "POST", path: "/reseller/collect-transactions-as-reseller"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     * @param int    $from
     * @param int    $to
     * @param int    $limit
     *
     * @return Transactions
     */
    public function collect(
        string $reseller,
        ?int $from,
        ?int $to,
        ?int $limit
    ) {
        return $this->collectTransactions->collect(
            $reseller,
            $from,
            $to,
            $limit
        );
    }
}
