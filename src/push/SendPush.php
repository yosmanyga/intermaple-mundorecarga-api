<?php

namespace Intermaple\Mundorecarga;

use ExponentPhpSDK\Exceptions\ExpoException;
use ExponentPhpSDK\Expo;

/**
 * @di\service()
 */
class SendPush
{
    /**
     * @cli\resolution({command: "/send-push"})
     */
    public function send()
    {
        $interestDetails = [
            uniqid(),
            'ExponentPushToken[-t-Bc6KbrqV99zIqXO8NCm]'
        ];

        $expo = Expo::normalSetup();

        $expo->subscribe($interestDetails[0], $interestDetails[1]);

        $notification = ['body' => 'Hello World!'];

        // Notify an interest with a notification
        try {
            $expo->notify($interestDetails[0], $notification);
        } catch (ExpoException $e) {
            throw new \LogicException(null, null, $e);
        }
    }
}
