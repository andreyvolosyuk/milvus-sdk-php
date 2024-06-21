<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;


use JsonSerializable;
use Milvus\Proto\Milvus\GrantEntity;

class GrantItem implements JsonSerializable
{
    /**
     * @var string
     */
    private $object;

    /**
     * @var string
     */
    private $objectName;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var string
     */
    private $grantorName;

    /**
     * @var string
     */
    private $privilege;

    public function __construct(string $object, string $objectName, string $dbName,
                                string $roleName, string $grantorName, string $privilege)
    {
        $this->object = $object;
        $this->objectName = $objectName;
        $this->dbName = $dbName;
        $this->roleName = $roleName;
        $this->grantorName = $grantorName;
        $this->privilege = $privilege;
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @return string
     */
    public function getObjectName(): string
    {
        return $this->objectName;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * @return string
     */
    public function getGrantorName(): string
    {
        return $this->grantorName;
    }

    /**
     * @return string
     */
    public function getPrivilege(): string
    {
        return $this->privilege;
    }

    /**
     * @param GrantEntity $grantEntity
     *
     * @return self
     */
    public static function fromGrantEntity(GrantEntity $grantEntity): self
    {
        return new static(
            $grantEntity->getObject()->getName(),
            $grantEntity->getObjectName(),
            $grantEntity->getDbName(),
            $grantEntity->getRole()->getName(),
            $grantEntity->getGrantor()->getUser()->getName(),
            $grantEntity->getGrantor()->getPrivilege()->getName()
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'object_type' => $this->object,
            'object_name' => $this->objectName,
            'db_name' => $this->dbName,
            'role_name' => $this->roleName,
            'privilege' => $this->privilege,
            'grantor_name' => $this->grantorName
        ];
    }
}