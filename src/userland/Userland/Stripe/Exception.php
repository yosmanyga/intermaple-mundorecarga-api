<?php

namespace Intermaple\Mundorecarga\Userland\Stripe;

class Exception extends \Exception implements \JsonSerializable
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->message
        ];
    }
}