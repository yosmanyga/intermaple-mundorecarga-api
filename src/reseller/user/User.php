<?php

namespace Intermaple\Mundorecarga\Reseller;

use Intermaple\Mundorecarga\Reseller\User\Provider;
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
     * @return array
     */
    public function getProviders(): array
    {
        return $this->offsetGet('providers');
    }

    /**
     * {@inheritdoc}
     */
    public function bsonUnserialize(array $data)
    {
        $data['id'] = $data['_id'];
        unset($data['_id']);

        $providers = [];
        foreach ($data['providers'] as $provider) {
            $providers[] = new Provider(
                $provider->id,
                $provider->discount
            );
        }
        $data['providers'] = $providers;
        
        parent::bsonUnserialize($data);
    }
}
