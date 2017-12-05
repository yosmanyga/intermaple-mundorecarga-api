<?php

namespace Intermaple\Mundorecarga;

class TopupAsClient implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $steps;

    /**
     * @var string
     */
    private $contact;

    /**
     * @var string
     */
    private $product;

    /**
     * @var float
     */
    private $sendValue;

    /**
     * @var int
     */
    private $date;

    /**
     * @param string $id
     * @param array  $steps
     * @param string $contact
     * @param string $product
     * @param float  $sendValue
     * @param int    $date
     */
    public function __construct(
        string $id,
        array $steps,
        string $contact,
        string $product,
        float $sendValue,
        int $date
    ) {
        $this->id = $id;
        $this->steps = $steps;
        $this->contact = $contact;
        $this->product = $product;
        $this->sendValue = $sendValue;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @return string
     */
    public function getContact(): string
    {
        return $this->contact;
    }

    /**
     * @return string
     */
    public function getProduct(): string
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getSendValue(): float
    {
        return $this->sendValue;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'steps' => $this->steps,
            'contact' => $this->contact,
            'product' => $this->product,
            'sendValue' => $this->sendValue,
            'date' => $this->date,
        ];
    }
}
