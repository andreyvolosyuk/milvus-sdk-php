<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;


use Milvus\Proto\Schema\DataType;
use Milvus\Proto\Schema\FloatArray;
use Milvus\Proto\Schema\ScalarField;
use Milvus\Proto\Schema\VectorField;
use ValueError;
use Volosyuk\MilvusPhp\Exceptions\AutoIDException;
use Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use Volosyuk\MilvusPhp\Exceptions\PrimaryKeyException;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;

class DataEntity
{
    /**
     * @var FieldSchema
     */
    private $field;

    /**
     * @var array
     */
    private $values;

    /**
     * @param string $name
     * @param int $dataType
     * @param array $values
     *
     * @throws ParamException
     * @throws \ReflectionException
     * @throws AutoIDException
     * @throws DataTypeNotSupportException
     * @throws PrimaryKeyException
     * @throws SchemaNotReadyException
     */
    public function __construct(string $name, int $dataType, array $values)
    {
        if (!$name) {
            throw new ParamException(sprintf(ExceptionMessage::MISSING_ENTITY_PARAM, "name"));
        }
        if (!$dataType) {
            throw new ParamException(sprintf(ExceptionMessage::MISSING_ENTITY_PARAM, "type"));
        }
        $this->field = new FieldSchema($name, $dataType);

        if (!$values) {
            throw new ParamException(sprintf(ExceptionMessage::MISSING_ENTITY_PARAM, "values"));
        }
        $this->values = $values;
    }

    /**
     * @return FieldSchema
     */
    public function getField(): FieldSchema
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getFirstValue()
    {
        return $this->values[0];
    }

    /**
     * @return int
     */
    public function getValuesCount(): int
    {
        return count($this->values);
    }

    /**
     * @return int
     *
     * @throws ValueError
     */
    public function getVectorSize(): int
    {
        if (is_array($this->getFirstValue())) {
            return count($this->getFirstValue());
        }

        throw new ValueError("Data is not a vector");
    }

    /**
     * @return FieldData
     *
     * @throws ParamException
     */
    public function toFieldData(): FieldData
    {
        try {
            $dim = $this->getVectorSize();
        } catch (ValueError $e) {
            $dim = null;
        }

        return new FieldData(
            $this->field->getName(),
            $this->field->getDataType(),
            $this->values,
            $dim
        );
    }
}