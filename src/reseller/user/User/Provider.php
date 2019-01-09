<?php

namespace Intermaple\Mundorecarga\Reseller\User;

class Provider implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $discount;

    /**
     * @param string $id
     * @param float $discount
     */
    public function __construct(string $id, float $discount)
    {
        $this->id = $id;
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'discount' => $this->getDiscount(),
        ];
    }
}
