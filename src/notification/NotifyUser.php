<?php

namespace Intermaple\Mundorecarga;

use GuzzleHttp\Exception\GuzzleException;
use Yosmy\Recharge;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * @di\service()
 */
class NotifyUser
{
    /**
     * @cli\resolution({command: "/notify-user"})
     *
     * @param string $token
     */
    public function notify($token)
    {
        try {
            $response = (new Client())->post(
                'https://exp.host/--/api/v2/push/send',
                [
                    'headers' => [
                        'accept' => 'application/json',
                        'accept-encoding' => 'gzip, deflate',
                        'content-type' => 'application/json'
                    ],
                    'json' => [
                        'to' => $token,
                        'title' => '123 Probando 3',
                        'body' => 'Esto es otra prueba'
                    ]
                ]
            );
        } catch (GuzzleException $e) {
            throw new \LogicException();
        }

        $data = json_decode($response->getBody()->getContents(), true);
    }
}
