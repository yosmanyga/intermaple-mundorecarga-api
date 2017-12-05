<?php

namespace Intermaple\Mundorecarga\Country;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

/**
 * @di\service({private: true})
 */
class ReducePhoto
{
    /**
     * @param string $image
     * @param string $width
     *
     * @return string
     *
     * @throws GuzzleException
     */
    public function reduce($image, $width)
    {
        [$type, $data] = explode(',', $image);

        unset($data);

        $client = new Client();

        try {
            /** @var Response $response */
            $response = $client->request(
                'POST',
                sprintf('http://imaginary:9000/thumbnail?width=%s', $width),
                [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen($image, 'r'),
                        ]
                    ]
                ]
            );
        } catch (GuzzleException $e) {
            throw $e;
        }

        $data = base64_encode((string) $response->getBody());

        $image = join(',', [$type, $data]);

        return $image;
    }
}
