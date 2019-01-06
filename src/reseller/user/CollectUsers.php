<?php

namespace Intermaple\Mundorecarga\Reseller;

/**
 * @di\service()
 */
class CollectUsers
{
    /**
     * @var SelectUserCollection
     */
    private $selectCollection;

    /**
     * @param SelectUserCollection $selectCollection
     */
    public function __construct(
        SelectUserCollection $selectCollection
    ) {
        $this->selectCollection = $selectCollection;
    }

    /**
     * @http\resolution({method: "POST", path: "/reseller/collect-users"})
     * @domain\authorization({roles: ["operator"]})
     *
     * @return Users
     */
    public function collect()
    {
        $cursor = $this->selectCollection->select()->find();

        return new Users($cursor);
    }
}