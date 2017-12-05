<?php

namespace Intermaple\Mundorecarga\Userland\Referral;

/**
 * @di\service()
 */
class CollectTopups
{
    /**
     * @var SelectTopupCollection
     */
    private $selectCollection;

    /**
     * @param SelectTopupCollection $selectCollection
     */
    public function __construct(SelectTopupCollection $selectCollection)
    {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string[] $ids
     *
     * @return Topups
     */
    public function collect(
        array $ids
    ) {
        $cursor = $this->selectCollection->select()->find([
            '_id' => ['$in' => $ids]
        ]);

        return new Topups($cursor);
    }
}
