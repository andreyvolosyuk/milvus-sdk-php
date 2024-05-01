<?php

namespace Volosyuk\MilvusPhp\ORM\Schema;


use Milvus\Proto\Common\KeyValuePair;
use Milvus\Proto\Schema\DataType;
use Milvus\Proto\Schema\FieldSchema as GRPCFieldSchema;
use ReflectionException;
use Volosyuk\MilvusPhp\Exceptions\AutoIDException;
use Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Exceptions\PrimaryKeyException;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;
use const Volosyuk\MilvusPhp\ORM\COMMON_TYPE_PARAMS;
use const Volosyuk\MilvusPhp\ORM\COMMON_TYPE_PARAMS_NORMALIZERS;
use const Volosyuk\MilvusPhp\ORM\VECTOR_DATA_TYPES;


class FieldSchema
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $dataType;

    /**
     * @var string
     */
    private $description = "";

    /**
     * @var bool
     */
    private $isPrimary = false;

    /**
     * @var bool
     */
    private $autoId = false;

    /**
     * @var array
     */
    private $typeParams = [];

    /**
     * @throws DataTypeNotSupportException
     * @throws PrimaryKeyException
     * @throws AutoIDException
     */
    public function __construct(string $name, int $data_type, string $description = "", array $typeParams = [])
    {
        $this->name = $name;
        $this->description = $description;

        # todo exception is risen, fix it
        if (!DataType::name($data_type)) {
            throw new DataTypeNotSupportException(ExceptionMessage::FIELD_D_TYPE);
        }
        $this->dataType = $data_type;

        $this->isPrimary = $typeParams['is_primary'] ?? false;
        if (!is_bool($this->isPrimary)) {
            throw new PrimaryKeyException(ExceptionMessage::IS_PRIMARY_TYPE);
        }

        $this->autoId = $typeParams['auto_id'] ?? false;
        if (!is_bool($this->autoId)) {
            throw new AutoIDException(ExceptionMessage::AUTO_ID_TYPE);
        }

        if (!$this->isPrimary and $this->autoId === true) {
            throw new PrimaryKeyException(ExceptionMessage::AUTO_ID_ONLY_ON_PK);
        }

        if (in_array($this->dataType, [DataType::BinaryVector, DataType::FloatVector, DataType::VarChar], true)) {
            $this->typeParams = array_intersect_key($typeParams, array_flip(COMMON_TYPE_PARAMS));
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return void
     */
    public function setPrimary()
    {
        $this->isPrimary = true;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * @return bool
     */
    public function isVector(): bool
    {
        return in_array($this->dataType, VECTOR_DATA_TYPES, true);
    }

    /**
     * @return int
     */
    public function getDataType(): int
    {
        return $this->dataType;
    }

    /**
     * @return void
     */
    public function setAutoId()
    {
        $this->autoId = true;
    }

    /**
     * @return bool
     */
    public function getAutoId(): bool
    {
        return $this->autoId;
    }

    /**
     * @param FieldSchema $fieldSchema
     *
     * @return bool
     */
    public function isEqual(self $fieldSchema): bool
    {
        return (
            $this->name === $fieldSchema->name &&
            $this->description === $fieldSchema->description &&
            $this->dataType === $fieldSchema->dataType &&
            $this->isPrimary === $fieldSchema->isPrimary &&
            $this->autoId === $fieldSchema->autoId &&
            $this->typeParams === $fieldSchema->typeParams
        );
    }

    /**
     * @return GRPCFieldSchema
     */
    public function toGRPC(): GRPCFieldSchema
    {
        $typeParams = [];
        foreach ($this->typeParams as $typeParamKey => $typeParamValue) {
            $kvPair = new KeyValuePair();
            $kvPair->setKey($typeParamKey);
            $kvPair->setValue(strval($typeParamValue));
            $typeParams[] = $kvPair;
        }

        return (new GRPCFieldSchema())
            ->setName($this->name)
            ->setDescription($this->description)
            ->setDataType($this->dataType)
            ->setAutoID($this->autoId)
            ->setIsPrimaryKey($this->isPrimary)
            ->setTypeParams($typeParams);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "name"          => $this->name,
            "description"   => $this->description,
            "type"          => $this->dataType,
            "is_primary"    => $this->isPrimary,
            "auto_id"       => $this->autoId,
            "params"        => $this->typeParams,
        ];
    }

    /**
     * @param GRPCFieldSchema $fs
     *
     * @return self
     *
     * @throws AutoIDException
     * @throws DataTypeNotSupportException
     * @throws PrimaryKeyException
     * @throws ReflectionException
     * @throws SchemaNotReadyException
     */
    public static function fromGRPC(GRPCFieldSchema $fs): self
    {
        $typeParams = [
            "is_primary"    => $fs->getIsPrimaryKey(),
            "auto_id"       => $fs->getAutoID(),
        ];

        foreach ($fs->getTypeParams() as $typeParam) {
            $key = $typeParam->getKey();

            if (in_array($key, COMMON_TYPE_PARAMS, true)) {
                $value = $typeParam->getValue();

                if (array_key_exists($key, COMMON_TYPE_PARAMS_NORMALIZERS) && is_callable(COMMON_TYPE_PARAMS_NORMALIZERS[$key])) {
                    $value = COMMON_TYPE_PARAMS_NORMALIZERS[$key]($value);
                }

                $typeParams[$key] = $value;
            }
        }

        return new static($fs->getName(), $fs->getDataType(), $fs->getDescription(), $typeParams);
    }
}