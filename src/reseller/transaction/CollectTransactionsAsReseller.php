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
        if (!in_array(
            $reseller, 
            [
                '5c8fc613a2e2d', // Allan
                '5ce56ecf4d49e', // Osmi
                '5cead618ba26a', // Esmeiquel
                '5d25632329732', // Lismary
            ]
        )) {
            return [];
        }

        return $this->collectTransactions->collect(
            $reseller,
            $from,
            $to,
            $limit
        );
    }
}
