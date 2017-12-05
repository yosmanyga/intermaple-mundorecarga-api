<?php

namespace Intermaple\Mundorecarga;

class Topups implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var Topup[]
     */
    private $cursor;

    /**
     * @param \Traversable $cursor
     */
    public function __construct(
        \Traversable $cursor
    ) {
        $this->cursor = $cursor;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->cursor;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $items = [];
        foreach ($this->cursor as $item) {
            $items[] = $item->jsonSerialize();
        }

        return $items;
    }
}
