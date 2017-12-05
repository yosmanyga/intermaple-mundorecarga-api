<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Model\BSONDocument;

class Transaction extends BSONDocument
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->offsetGet('id');
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->offsetGet('user');
    }

    /**
     * @return string The bank transaction reference
     */
    public function getReference(): string
    {
        return $this->offsetGet('reference');
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->offsetGet('amount');
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->offsetGet('date');
    }

    /**
     * {@inheritdoc}
     */
    public function bsonUnserialize(array $data)
    {
        $data['id'] = $data['_id'];
        unset($data['_id']);

        /** @var UTCDateTime $date */
        $date = $data['date'];
        $data['date'] = $date->toDateTime()->getTimestamp();

        parent::bsonUnserialize($data);
    }
}
