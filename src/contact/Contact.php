<?php

namespace Intermaple\Mundorecarga;

use MongoDB\Model\BSONDocument;

class Contact extends BSONDocument
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
    public function getCountry(): string
    {
        return $this->offsetGet('country');
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->offsetGet('prefix');
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->offsetGet('account');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->offsetGet('type');
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->offsetGet('provider');
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
