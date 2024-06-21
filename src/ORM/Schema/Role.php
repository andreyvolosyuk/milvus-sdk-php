<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;


use Volosyuk\MilvusPhp\Client\ClientAccessor;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Exceptions\MilvusException;
use Volosyuk\MilvusPhp\Exceptions\ParamException;

class Role
{
    use ClientAccessor;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array|GrantItem[]
     */
    private $privileges;

    public function __construct(string $name, array $privileges = [])
    {
        $this->name = $name;
        $this->privileges = $privileges;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|GrantItem[]
     */
    public function getPrivileges(): array
    {
        return $this->privileges;
    }

    /**
     * @throws ParamException|GRPCException|MilvusException
     */
    public function drop()
    {
        $this
            ->getClient()
            ->dropRole($this->name);
    }
}