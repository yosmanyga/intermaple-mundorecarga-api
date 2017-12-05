<?php

namespace Intermaple\Mundorecarga\Reseller;

use Yosmy\Recharge;

/**
 * @di\service()
 */
class AddAgent
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
     * @http\resolution({method: "POST", path: "/reseller/add-agent"})
     * @domain\authorization({roles: ["reseller"]})
     *
     * @param string $reseller
     * @param string $name
     */
    public function add(
        string $reseller,
        string $name
    ) {
        $this->selectCollection->select()->insertOne([
            '_id' => uniqid(),
            'user' => $reseller,
            'account' => $name,
            'deleted' => false
        ]);
    }
}
