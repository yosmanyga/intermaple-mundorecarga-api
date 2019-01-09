<?php

namespace Intermaple\Mundorecarga;

use Yosmy\Userland\Stripe;

/**
 * @di\service()
 */
class ImportDisputes
{
    /**
     * @var Stripe\ImportDisputes
     */
    private $importDisputes;

    /**
     * @param Stripe\ImportDisputes $importDisputes
     */
    public function __construct(Stripe\ImportDisputes $importDisputes)
    {
        $this->importDisputes = $importDisputes;
    }

    /**
     * @cli\resolution({command: "/import-disputes"})
     */
    public function load()
    {
        $this->importDisputes->import();
    }
}
