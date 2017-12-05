<?php

namespace Intermaple\Mundorecarga\Cli;

use Yosmy\ReportError;
use Symsonte\Cli\Server\CommandCaller as BaseCommandCaller;

/**
 * @di\service({
 *     private: true
 * })
 */
class CommandCaller implements BaseCommandCaller
{
    /**
     * @var ReportError
     */
    private $reportError;

    /**
     * @param ReportError $reportError
     */
    public function __construct(ReportError $reportError)
    {
        $this->reportError = $reportError;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Throwable
     */
    public function call($command, $method, $parameters)
    {
        $this->reportError->register();

        try {
            $payload = call_user_func_array([$command, $method], $parameters);

            $response = $payload;
        } catch (\Exception $e) {
            $class = get_class($command);

            $payload = null;

            if ($e instanceof \JsonSerializable) {
                $payload = $e->jsonSerialize();
            }

            if (strpos($class, 'Intermaple\Mundorecarga') === 0) {
                $response = [
                    'code' => $this->generateKey($e),
                    'payload' => $payload
                ];
            } else {
                throw $e;
            }
        } catch (\Throwable $e) {
            throw $e;
        }

        return $response;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function generateKey($class)
    {
        return strtolower(
            strtr(
                preg_replace(
                    '/(?<=[a-zA-Z0-9])[A-Z]/',
                    '-\\0',
                    $class
                ),
                '\\',
                '.'
            )
        );
    }
}
