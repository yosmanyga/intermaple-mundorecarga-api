<?php

namespace Intermaple\Mundorecarga\Blacklist;

use MongoDB\Model\BSONDocument;

class Contact extends BSONDocument
{
    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->offsetGet('number');
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
