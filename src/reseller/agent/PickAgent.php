<?php

namespace Intermaple\Mundorecarga\Reseller;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class PickAgent
{
    /**
     * @var SelectAgentCollection
     */
    private $selectCollection;

    /**
     * @param SelectAgentCollection $selectCollection
     */
    public function __construct(
        SelectAgentCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @param string $id
     * @param string $reseller
     *
     * @return Agent
     */
    public function pick(
        string $id,
        string $reseller
    ) {
        /** @var Agent $agent */
        $agent = $this->selectCollection->select()->findOne([
            '_id' => $id,
            'user' => $reseller,
        ]);

        if (!$agent) {
            throw new \LogicException();
        }

        return $agent;
    }
}
