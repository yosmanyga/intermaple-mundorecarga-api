<?php

namespace Intermaple\Mundorecarga;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Symsonte\Http\Server;

/**
 * @di\service()
 */
class ForwardRequest
{
    /**
     * @http\resolution({method: "POST", path: "/forward-request"})
     *
     * @param $method
     * @param $uri
     * @param array $options
     *
     * @return Server\OrdinaryResponse
     */
    public function forward($method, $uri, $options = [])
    {
        try {
            $response = (new Client)->request(
                $method,
                $uri,
                $options
            );

            return new Server\OrdinaryResponse(
                json_decode((string) $response->getBody()),
                $response->getStatusCode(),
                $response->getHeaders()
            );
        } catch (RequestException $e) {
            return new Server\OrdinaryResponse(
                json_decode((string) $e->getResponse()->getBody()),
                $e->getResponse()->getStatusCode(),
                $e->getResponse()->getHeaders()
            );
        } catch (GuzzleException $e) {
            throw new \LogicException(null, null, $e);
        }
    }
}