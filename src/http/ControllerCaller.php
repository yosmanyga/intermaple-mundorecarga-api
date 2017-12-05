<?php

namespace Intermaple\Mundorecarga\Http;

use Yosmy\ReportError;
use Symsonte\Http\Server\ControllerCaller as BaseControllerCaller;
use Symsonte\Http\Server\OrdinaryResponse;

/**
 * @di\service({
 *     private: true
 * })
 */
class ControllerCaller implements BaseControllerCaller
{
    /**
     * @var string
     */
    private $env;

    /**
     * @var ReportError
     */
    private $reportError;

    /**
     * @di\arguments({
     *     env: "%env%"
     * })
     *
     * @param string $env
     * @param ReportError $reportError
     */
    public function __construct(
        string $env,
        ReportError $reportError
    ) {
        $this->env = $env;
        $this->reportError = $reportError;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Throwable
     */
    public function call($controller, $method, $parameters)
    {
        if ($this->env != 'dev') {
            $this->reportError->register();
        }

        try {
            $result = call_user_func_array([$controller, $method], $parameters);

            if ($result instanceof OrdinaryResponse) {
                return $result;
            }

            $response = [
                'code' => 'success',
                'payload' => $result
            ];
        } catch (\Exception $e) {
            $payload = null;

            if ($e instanceof \JsonSerializable) {
                $payload = $e->jsonSerialize();
            }

            if (strpos(get_class($e), 'Intermaple\Mundorecarga') === 0) {
                $code = $this->generateKey(
                    str_replace(
                        'Intermaple\\Mundorecarga\\',
                        '',
                        get_class($e)
                    )
                );

                $response = [
                    'code' => $code,
                    'payload' => $payload
                ];
            } else {
                if ($this->env != 'dev') {
                    $this->reportError->report($e);
                }

                $response = [
                    'code' => 'unexpected-exception',
                ];
            }
        } catch (\Throwable $e) {
            if ($this->env != 'dev') {
                $this->reportError->report($e);
            }

            $response = [
                'code' => 'unexpected-exception',
            ];
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
