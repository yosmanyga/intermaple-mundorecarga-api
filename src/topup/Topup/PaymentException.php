<?php

namespace Intermaple\Mundorecarga\Topup;

class PaymentException extends \Exception implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'message' => $this->message
        ];
    }

}