<?php

namespace Intermaple\Mundorecarga\Country;

use MongoDB\Model\BSONDocument;

class Photo extends BSONDocument
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->offsetGet('id');
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->offsetGet('country');
    }

    /**
     * @return string
     */
    public function getOriginal()
    {
        return $this->offsetGet('original');
    }

    /**
     * @return string
     */
    public function getNormal()
    {
        return $this->offsetGet('normal');
    }

    /**
     * @return string
     */
    public function getSmall()
    {
        return $this->offsetGet('small');
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
