<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\Model\BSONDocument;

class Country extends BSONDocument
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
     * @return string
     */
    public function getIso(): string
    {
        return $this->offsetGet('iso');
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->offsetGet('discount');
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
