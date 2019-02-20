<?php

namespace Intermaple\Mundorecarga\Userland;

use Yosmy\Userland\Authentication;

class ResellerAuthentication implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Authentication\Phone
     */
    private $phone;

    /**
     * @var string[]
     */
    private $roles;

    /**
     * @param string               $id
     * @param string               $token
     * @param Authentication\Phone $phone
     * @param string[]             $roles
     */
    public function __construct(
        string $id,
        string $token,
        Authentication\Phone $phone,
        array $roles
    ) {
        $this->id = $id;
        $this->phone = $phone;
        $this->token = $token;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return Authentication\Phone
     */
    public function getPhone(): Authentication\Phone
    {
        return $this->phone;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'phone' => $this->getPhone(),
            'token' => $this->getToken(),
            'roles' => $this->getRoles(),
        ];
    }
}
