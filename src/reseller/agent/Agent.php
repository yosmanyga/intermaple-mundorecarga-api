<?php

namespace Intermaple\Mundorecarga\Reseller;

use MongoDB\Model\BSONDocument;

class Agent extends BSONDocument
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
    public function getName(): string
    {
        return $this->offsetGet('name');
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->offsetGet('deleted');
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
