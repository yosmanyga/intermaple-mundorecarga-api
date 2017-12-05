<?php

namespace Intermaple\Mundorecarga\Topup;

class ContactException extends \Exception implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'message' => $this->message
        ];
    }

}