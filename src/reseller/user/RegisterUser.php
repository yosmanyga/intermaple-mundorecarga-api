<?php

namespace Intermaple\Mundorecarga\Reseller;

use Yosmy\Userland\ExecuteRegistration;

/**
 * @di\service()
 */
class RegisterUser
{
    /**
     * @var ExecuteRegistration
     */
    private $executeRegistration;

    /**
     * @var SelectUserCollection
     */
    private $selectUserCollection;

    /**
     * @var AddAgent
     */
    private $addAgent;

    /**
     * @param ExecuteRegistration   $executeRegistration
     * @param SelectUserCollection  $selectUserCollection
     * @param AddAgent $addAgent
     */
    public function __construct(
        ExecuteRegistration $executeRegistration, 
        SelectUserCollection $selectUserCollection, 
        AddAgent $addAgent
    ) {
        $this->executeRegistration = $executeRegistration;
        $this->selectUserCollection = $selectUserCollection;
        $this->addAgent = $addAgent;
    }

    /**
     * @cli\resolution({command: "/reseller/register-user"})
     *
     * @param string $country
     * @param string $prefix
     * @param string $number
     * @param string $name
     *
     * @return string
     */
    public function add(
        string $country,
        string $prefix,
        string $number,
        string $name
    ) {
        $id = $this->executeRegistration->execute(
            $country,
            $prefix,
            $number,
            ['reseller']
        );

        $this->selectUserCollection->select()->insertOne([
            '_id' => $id,
            'name' => $name,
            'balance' => 0,
            'providers' => []
        ]);
        
        $this->addAgent->add($id, $name);

        return $id;
    }
}