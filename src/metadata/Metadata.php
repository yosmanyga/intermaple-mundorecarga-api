<?php

namespace Intermaple\Mundorecarga;

use MongoDB\Model\BSONDocument;

class Metadata extends BSONDocument
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
    public function getDescription(): string
    {
        return $this->offsetGet('description');
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->offsetGet('value');
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
