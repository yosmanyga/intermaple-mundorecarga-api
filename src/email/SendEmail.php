<?php

namespace Yosmy\Userland;

use Mailgun\Mailgun;

/**
 * @di\service()
 */
class SendEmail
{
    /**
     * @var string
     */
    private $mailgunApiKey;

    /**
     * @var string
     */
    private $mailgunFrom;

    /**
     * @param string $mailgunApiKey
     * @param string $mailgunFrom
     *
     * @di\arguments({
     *     mailgunApiKey: '%mailgun_api_key%',
     *     mailgunFrom:   '%mailgun_from%'
     * })
     */
    public function __construct(
        $mailgunApiKey,
        $mailgunFrom
    )
    {
        $this->mailgunApiKey = $mailgunApiKey;
        $this->mailgunFrom = $mailgunFrom;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $text
     */
    public function send($to, $subject, $text)
    {
        $mg = Mailgun::create($this->mailgunApiKey);

        $parts = explode('@', $this->mailgunFrom);

        $mg->messages()->send(
            $parts[1],
            [
                'from' => $this->mailgunFrom,
                'to' => $to,
                'subject' => $subject,
                'text' => $text
            ]
        );
    }
}
