<?php

namespace Intermaple\Mundorecarga;

use Symsonte\JsApi\GenerateCode;

/**
 * @di\service()
 */
class GenerateApi
{
    /**
     * @var GenerateCode
     */
    private $generateCode;

    /**
     * @param GenerateCode $generateCode
     */
    public function __construct(GenerateCode $generateCode)
    {
        $this->generateCode = $generateCode;
    }

    /**
     * @cli\resolution({command: "/generate-api"})
     */
    public function generate()
    {
        $code = $this->generateCode->generate('Intermaple\Mundorecarga');

        file_put_contents("/var/www/Api.js", $code);
    }
}