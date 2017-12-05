<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

use MongoDB\Model\BSONDocument;

class User extends BSONDocument
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
    public function getCode(): string
    {
        return $this->offsetGet('code');
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->offsetGet('balance');
    }

    /**
     * @return array
     */
    public function getReferrals(): array
    {
        return $this->offsetGet('referrals');
    }

    /**
     * {@inheritdoc}
     */
    public function bsonUnserialize(array $data)
    {
        $data['id'] = $data['_id'];
        unset($data['_id']);

        parent::bsonUnserialize($data);
    }
}
