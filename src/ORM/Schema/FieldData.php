<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;


use Google\Protobuf\Internal\RepeatedField;
use Milvus\Proto\Schema\BoolArray;
use Milvus\Proto\Schema\DataType;
use Milvus\Proto\Schema\DoubleArray;
use Milvus\Proto\Schema\FieldData as FieldDataGRPC;
use Milvus\Proto\Schema\FloatArray;
use Milvus\Proto\Schema\IntArray;
use Milvus\Proto\Schema\LongArray;
use Milvus\Proto\Schema\ScalarField;
use Milvus\Proto\Schema\StringArray;
use Milvus\Proto\Schema\VectorField;
use TypeError;
use UnexpectedValueException;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use const Volosyuk\MilvusPhp\ORM\VECTOR_DATA_TYPES;

class FieldData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $type;

    private $data;

    /**
     * @var int|null
     */
    private $dim;

    /**
     * @param string $name
     * @param int $type
     * @param $data
     * @param int|null $dim
     *
     * @throws ParamException
     */
    public function __construct(string $name, int $type, $data = [], int $dim = null)
    {
        if (!$name) {
            throw new ParamException("Field data name must be specified");
        }
        $this->name = $name;

        try {
            DataType::name($type);
        } catch (UnexpectedValueException $e) {
            throw new ParamException($e);
        }
        $this->type = $type;

        if (in_array($type, VECTOR_DATA_TYPES, true) && !$dim) {
            throw new ParamException(sprintf(
                "Dimension must be specified for %s data type",
                DataType::name($type)
            ));
        }
        $this->dim = $dim;
        $this->data = $data;
    }

    /**
     * @param FieldDataGRPC $field
     *
     * @return array|false|RepeatedField
     */
    public static function rawToData(FieldDataGRPC $field)
    {
        $type = $field->getType();

        if (array_key_exists($type, SCALARS_DATA_TYPE_FIELD_MAPPER)) {
            $scalarField = $field->getScalars();
            $dataTypeString = scalarToStringType($type);
            $dataGetterName = "get{$dataTypeString}Data";
            if (method_exists($scalarField, $dataGetterName)) {
                /**
                 * @var LongArray|StringArray|IntArray|BoolArray|FloatArray|DoubleArray $data
                 */
                $dataArray = $scalarField->{$dataGetterName}();
                return $dataArray->getData();
            }
        } elseif ($type === DataType::FloatVector) {
            return $field
                ->getVectors()
                ->getFloatVector()
                ->getData();
        } elseif ($type === DataType::BinaryVector) {
            # todo find difference between chars and binary vector
            $dim = static::rawToDim($field);

            $binaryVector = $field
                ->getVectors()
                ->getBinaryVector();

            return str_split($binaryVector, $dim);
        }

        return [];
    }

    /**
     * @param FieldDataGRPC $field
     *
     * @return int|null
     */
    public static function rawToDim(FieldDataGRPC $field)
    {
        if ($field->getType() == DataType::FloatVector) {
            return $field
                ->getVectors()
                ->getDim();
        } elseif ($field->getType() == DataType::BinaryVector) {
            return $field
                ->getVectors()
                ->getDim() / 8;
        }

        return null;
    }

    /**
     * @throws ParamException
     */
    public static function fromRaw(FieldDataGRPC $field): self
    {
        return new static(
            $field->getFieldName(),
            $field->getType(),
            static::rawToData($field),
            static::rawToDim($field)
        );
    }

    /**
     * @return FieldDataGRPC
     *
     * @throws TypeError
     */
    public function toRaw(): FieldDataGRPC
    {
        $fieldData = (new FieldDataGRPC())
            ->setFieldName($this->name)
            ->setType($this->type);

        if (array_key_exists($this->type, SCALARS_DATA_TYPE_FIELD_MAPPER)) {
            $dataArray = scalarToDataArray($this->type)
                ->setData($this->data);
            $scalarField = new ScalarField();
            $dataTypeString = scalarToStringType($this->type);
            $dataSetterName = "set{$dataTypeString}Data";
            if (method_exists($scalarField, $dataSetterName)) {
                $scalarField->{$dataSetterName}($dataArray);
            }
            $fieldData->setScalars($scalarField);

            return $fieldData;
        } elseif ($this->type === DataType::FloatVector) {
            $vectorData = (new FloatArray())
                ->setData(array_merge(...$this->data));

            $vectorField = (new VectorField())
                ->setDim($this->dim)
                ->setFloatVector($vectorData);

            return $fieldData->setVectors($vectorField);
        } elseif ($this->type === DataType::BinaryVector) {
            # todo find difference between chars and binary vector
            $vectorField = (new VectorField())
                ->setDim($this->dim * 8)
                ->setBinaryVector(implode('', $this->data));

            return $fieldData->setVectors($vectorField);
        }

        throw new TypeError(sprintf(
            "Invalid data type %s",
            DataType::name($this->type)
        ));
    }
}