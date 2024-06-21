<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;


use Volosyuk\MilvusPhp\Client\ClientAccessor;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Exceptions\MilvusException;

class User
{
    use ClientAccessor;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array|Role[]
     */
    private $roles;

    public function __construct(string $name, array $roles = [])
    {
        $this->name = $name;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return void
     *
     * @throws GRPCException|MilvusException
     */
    public function drop()
    {
        $this
            ->getClient()
            ->dropUser($this->name);
    }
}
