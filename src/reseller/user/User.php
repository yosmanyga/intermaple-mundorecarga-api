<?php

namespace Intermaple\Mundorecarga\Reseller;

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
    public function getName(): string
    {
        return $this->offsetGet('name');
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->offsetGet('balance');
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
