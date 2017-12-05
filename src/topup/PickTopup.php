<?php

namespace Intermaple\Mundorecarga;

/**
 * @di\service()
 */
class PickTopup
{
    /**
     * @var SelectTopupCollection
     */
    private $selectCollection;

    /**
     * @param SelectTopupCollection $selectCollection
     */
    public function __construct(
        SelectTopupCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/pick-topup"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @param string $id
     *
     * @return Topup
     */
    public function pick(
        string $id
    ) {
        $criteria = [];

        $criteria['_id'] = $id;

        /** @var Topup $topup */
        $topup = $this->selectCollection->select()->findOne($criteria);

        if (is_null($topup)) {
            throw new \LogicException();
        }

        return $topup;
    }
}
