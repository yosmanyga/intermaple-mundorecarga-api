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
     * @return string
     */
    public function getAccount(): string
    {
        return $this->offsetGet('account');
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
     * Ding topup id
     *
     * @return string
     */
    public function getDing(): string
    {
        return $this->offsetGet('ding');
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->offsetGet('attempts');
    }

    /**
     * @return float
     */
    public function getCharge(): float
    {
        return $this->offsetGet('charge');
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
     * @return array
     */
    public function getReceive(): array
    {
        return $this->offsetGet('receive');
    }

    /**
     * @return array
     */
    public function getCurrency(): array
    {
        return $this->offsetGet('currency');
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
