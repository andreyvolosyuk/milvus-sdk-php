<?php

namespace Volosyuk\MilvusPhp\ORM\Schema;

use Countable;
use Milvus\Proto\Schema\CollectionSchema as GRPCCollectionSchema;
use Milvus\Proto\Schema\DataType as DataType;
use ReflectionException as ReflectionExceptionAlias;
use Volosyuk\MilvusPhp\Exceptions\AutoIDException;
use Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Exceptions\FieldNotFoundException;
use Volosyuk\MilvusPhp\Exceptions\FieldTypeException;
use Volosyuk\MilvusPhp\Exceptions\PrimaryKeyException;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;
use const Volosyuk\MilvusPhp\ORM\PRIMARY_KEY_TYPES;
use const Volosyuk\MilvusPhp\ORM\VECTOR_DATA_TYPES;

class CollectionSchema implements Countable {
    /**
     * @var FieldSchema[]
     */
    private $fields;

    /**
     * @var FieldSchema
     */
    private $primaryField;

    /**
     * @var bool
     */
    private $autoId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $collectionParams;

    /**
     * @throws FieldTypeException
     * @throws PrimaryKeyException
     * @throws AutoIDException
     * @throws SchemaNotReadyException
     */
    public function __construct(array $fields, string $description = '', array $collectionParams = [])
    {
        $this->fields = $fields;
        if (!$this->fields) {
            throw new SchemaNotReadyException();
        }

        $this->description = $description;
        $primaryFieldName = $collectionParams['primary_field'] ?? null;
        $this->collectionParams = $collectionParams;

        foreach ($this->fields as $field) {
            if (!($field instanceof FieldSchema)) {
                throw new FieldTypeException(ExceptionMessage::FIELD_TYPE);
            }

            if ($field->getName() === $primaryFieldName) {
                $field->setPrimary();
            }
        }

        foreach ($this->fields as $field) {
            if ($field->isPrimary()) {
                if ($this->primaryField && $this->primaryField->getName() !== $field->getName()) {
                    throw new PrimaryKeyException(ExceptionMessage::PRIMARY_KEY_ONLY_ONE);
                }

                $this->primaryField = $field;
            }
        }

        if (!$this->primaryField) {
            throw new PrimaryKeyException(ExceptionMessage::PRIMARY_KEY_NOT_EXIST);
        }

        if (!in_array($this->primaryField->getDataType(), PRIMARY_KEY_TYPES, true)) {
            throw new PrimaryKeyException(ExceptionMessage::PRIMARY_KEY_TYPE);
        }

        if (array_key_exists('auto_id', $collectionParams)) {
            $autoId = $collectionParams['auto_id'];

            if (!is_bool($autoId)) {
                throw new AutoIDException(ExceptionMessage::AUTO_ID_TYPE);
            }

            if ($autoId === true) {
                $this->primaryField->setAutoId();
            }

            if ($this->primaryField->getDataType() !== DataTypeAlias::Int64) {
                throw new AutoIDException(ExceptionMessage::AUTO_ID_FIELD_TYPE);
            }
        } else {
            $autoId = $this->primaryField->getAutoId();
        }

        // at least one field must have vector type
        $vectorFieldsNum = array_reduce($this->fields, function ($carry, FieldSchema $field) {
            return $carry + intval($field->isVector());
        }, 0);
        if ($vectorFieldsNum < 1) {
            throw new SchemaNotReadyException(ExceptionMessage::NO_VECTOR);
        }

        $this->autoId = $autoId;
        $this->collectionParams = $collectionParams;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->fields);
    }

    /**
     * @return array|FieldSchema[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return FieldSchema
     *
     * @throws FieldNotFoundException
     */
    public function getFieldByName(string $fieldName): FieldSchema
    {
        foreach ($this->fields as $field) {
            if ($field->getName() === $fieldName) {
                return $field;
            }
        }

        throw new FieldNotFoundException(sprintf(
            ExceptionMessage::FIELD_NOT_FOUND_IN_SCHEMA,
            $fieldName
        ));
    }

    /**
     * @param CollectionSchema $collectionSchema
     *
     * @return bool
     *
     * @todo test properly
     */
    public function isEqual(self $collectionSchema): bool
    {
        $schemaAttributesEquality = (
            $this->description === $collectionSchema->description &&
            $this->autoId === $collectionSchema->autoId
        );

        $srcFieldArray = [];
        foreach ($this->fields as $field) {
            $srcFieldArray[$field->getName()] = $field->toArray();
        }

        $counterpartFieldArray = [];
        foreach ($collectionSchema->fields as $field) {
            $counterpartFieldArray[$field->getName()] = $field->toArray();
        }

        return (
            $schemaAttributesEquality &&
            $srcFieldArray == $counterpartFieldArray
        );
    }

    /**
     * @return GRPCCollectionSchema
     */
    public function toGRPC(): GRPCCollectionSchema
    {
        $fields = [];
        foreach ($this->fields as $field) {
            $fields[] = $field->toGRPC();
        }

        return (new GRPCCollectionSchema())
            ->setDescription($this->description)
            ->setFields($fields);
    }

    /**
     * @throws PrimaryKeyException
     * @throws AutoIDException
     * @throws ReflectionExceptionAlias
     * @throws SchemaNotReadyException
     * @throws DataTypeNotSupportException
     * @throws FieldTypeException
     */
    public static function fromGRPC(GRPCCollectionSchema $cs): self
    {
        $fields = [];
        foreach ($cs->getFields() as $csField) {
            $fields[] = FieldSchema::fromGRPC($csField);
        }

        return new self($fields, $cs->getDescription());
    }

    public function toArray(): array
    {
        $array = [
            'auto_id' => $this->autoId,
            'description' => $this->description,
            'fields' => []
        ];

        foreach ($this->fields as $field) {
            $array['fields'][] = $field->toArray();
        }

        return $array;
    }

    /**
     * @return bool
     */
    public function hasAutoId(): bool
    {
        return $this->autoId;
    }
}
