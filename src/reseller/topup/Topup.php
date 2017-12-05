<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\Model\BSONDocument;
use MongoDB\BSON\UTCDateTime;

class Topup extends BSONDocument
{
    const STEP_TRANSFER_SUCCESS = 'transfer.success';
    const STEP_TRANSFER_EXCEPTION = 'transfer.exception';

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
    public function getAgent(): string
    {
        return $this->offsetGet('agent');
    }

    /**
     * Ding topup id
     *
     * @return string
     */
    public function getDing(): string
    {
        return $this->offsetGet('ding');
    }

    /**
     * @return string
     */
    public function getProduct(): string
    {
        return $this->offsetGet('product');
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
    public function getAttempts(): int
    {
        return $this->offsetGet('attempts');
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->offsetGet('date');
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->offsetGet('steps');
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
