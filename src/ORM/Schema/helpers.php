<?php

namespace Volosyuk\MilvusPhp\ORM\Schema;


use Google\Protobuf\Internal\RepeatedField;
use Milvus\Proto\Common\KeyValuePair;
use Milvus\Proto\Schema\BoolArray;
use Milvus\Proto\Schema\DataType as DataType;
use Milvus\Proto\Schema\DoubleArray;
use Milvus\Proto\Schema\FloatArray;
use Milvus\Proto\Schema\IDs;
use Milvus\Proto\Schema\IntArray;
use Milvus\Proto\Schema\LongArray;
use Milvus\Proto\Schema\StringArray;
use ReflectionException;
use ValueError;
use Volosyuk\MilvusPhp\Exceptions\AutoIDException;
use Volosyuk\MilvusPhp\Exceptions\DataNotMatchException;
use Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use Volosyuk\MilvusPhp\Exceptions\PrimaryKeyException;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;

/**
 * @param float|bool|int|string $datum
 *
 * @return int
 *
 * @throws DataTypeNotSupportException
 */
function inferDataType($datum): int
{
    if (is_float($datum)) {
        return DataType::Double;
    } elseif (is_bool($datum)) {
        return DataType::Bool;
    } elseif (is_int($datum)) {
        return DataType::Int64;
    } elseif (is_string($datum)) {
        return DataType::VarChar;
    } elseif (is_array($datum) && is_float($datum[0])) {
        return DataType::FloatVector;
    }

    # todo support binary

    throw new DataTypeNotSupportException(sprintf(ExceptionMessage::UNKNOWN_DATA_TYPE, $datum));
}

/**
 * @return int[]
 *
 * @throws DataTypeNotSupportException
 */
function parseFieldsFromData(array $data): array
{
    $inferredDataTypes = [];

    foreach ($data as $datum) {
        if (!is_array($datum)) {
            throw new DataTypeNotSupportException("Data should be a list of lists");
        }

        if (count($datum) < 1) {
            throw new DataTypeNotSupportException("Each array of data must not be empty");
        }

        $inferredDataTypes[] = inferDataType($datum[0]);
    }

    return $inferredDataTypes;
}

/**
 * @throws DataTypeNotSupportException
 * @throws DataNotMatchException
 */
function checkInsertDataSchema(CollectionSchema $schema, array $data)
{
    $inferredDataTypes = parseFieldsFromData($data);

    $basicFields = array_filter($schema->getFields(), function (FieldSchema $fieldSchema) {
        return !($fieldSchema->isPrimary() && $fieldSchema->getAutoId());
    });

    if (count($inferredDataTypes) !== count($basicFields)) {
        throw new DataNotMatchException("Data and collection schemas do not match");
    }

    array_map(function (int $inferredDataType, FieldSchema $basiField) {
        if ($inferredDataType !== $basiField->getDataType()) {
            throw new DataNotMatchException(sprintf(
                "The data type of field %s doesn't match, expected: %s, got %s",
                $basiField->getName(),
                DataType::name($basiField->getDataType()),
                DataType::name($inferredDataType)
            ));
        }
    }, $inferredDataTypes, $basicFields);
}


/**
 * @param CollectionSchema $schema
 * @param array $data
 *
 * @return DataEntity[]
 *
 * @throws DataNotMatchException
 * @throws ParamException
 */
function prepareInsertData(CollectionSchema $schema, array $data): array
{
    if ($schema->hasAutoId() && count($data) !== (count($schema) - 1)) {
        throw new DataNotMatchException(ExceptionMessage::FIELDS_NUM_INCONSISTENT);
    }

    $entities = [];
    $fieldKey = 0;
    $prevDataSetCount = null;
    foreach ($schema->getFields() as $field) {
        if ($field->isPrimary() && $field->getAutoId()) {
            continue;
        }

        $entities[] = new DataEntity(
            $field->getName(),
            $field->getDataType(),
            $data[$fieldKey]
        );

        if ($prevDataSetCount !== null && count($data[$fieldKey]) !== $prevDataSetCount) {
            throw new DataNotMatchException(ExceptionMessage::DATA_LENGTHS_INCONSISTENT);
        }

        $prevDataSetCount = count($data[$fieldKey]);
        $fieldKey++;
    }

    return $entities;
}

/**
 * @param int $dataType
 *
 * @return string
 *
 * @throws ValueError
 */
function scalarToStringType(int $dataType): string
{
    $dataTypeStr = SCALARS_DATA_TYPE_FIELD_MAPPER[$dataType] ?? null;

    if (is_null($dataTypeStr)) {
        throw new ValueError("$dataType is unknown");
    }

    return $dataTypeStr;
}

/**
 * @param int $dataType
 *
 * @return BoolArray|IntArray|LongArray|FloatArray|DoubleArray|StringArray
 *
 * @throws ValueError
 */
function scalarToDataArray(int $dataType)
{
    $dataTypeStr = scalarToStringType($dataType);

    $clsFQN = sprintf("Milvus\Proto\Schema\%sArray", $dataTypeStr);
    if (!class_exists($clsFQN)) {
        throw new ValueError("$clsFQN class not found");
    }

    return (new $clsFQN());
}

/**
 * @param array $params
 *
 * @return KeyValuePair[]
 */
function arrayToKeyValuePairs(array $params): array
{
    $keyValuePairs = [];

    foreach ($params as $key => $value) {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $keyValuePairs[] = (new KeyValuePair())
            ->setKey($key)
            ->setValue($value);
    }

    return $keyValuePairs;
}

/**
 * @param RepeatedField|KeyValuePair[] $keyValuePairs
 *
 * @return array
 */
function keyValuePairsToArray(RepeatedField $keyValuePairs): array
{
    $params = [];

    /**
     * @var KeyValuePair $keyValuePair
     */
    foreach ($keyValuePairs->getIterator() as $keyValuePair) {
        $v = json_decode($keyValuePair->getValue(), true);
        $params[$keyValuePair->getKey()] = (
        is_null($v) ? $keyValuePair->getValue() : $v
        );
    }

    return $params;
}

function repeatedFieldToArray(RepeatedField $repeatedField): array
{
    return iterator_to_array($repeatedField->getIterator());
}

/**
 * @param RepeatedField|KeyValuePair[] $keyValuePairs
 *
 * @return mixed
 *
 * @throws ParamException
 */
function keyValuePairsSearchByName($keyName, RepeatedField $keyValuePairs, $default = null)
{
    foreach ($keyValuePairs as $keyValuePair) {
        if ($keyValuePair->getKey() === $keyName) {
            return $keyValuePair->getValue();
        }
    }

    if (!is_null($default)) {
        return $default;
    }

    throw new ParamException(sprintf("No %s key in key value pairs", $keyName));
}

/**
 * @param RepeatedField $rf
 * @param int $offset
 * @param int $length
 *
 * @return array
 *
 * @throws ParamException
 */
function repeatedFieldSlice(RepeatedField $rf, int $offset, int $length): array
{
    $rightIndex = min($offset + $length, count($rf));

    if ($offset >= count($rf)) {
        throw new ParamException("Offset must be smaller than Repeated field length");
    }

    $res = [];
    for ($i = $offset; $i < $rightIndex; $i++) {
        $res[] = $rf[$i];
    }

    return $res;
}

/**
 * @throws ParamException
 */
function idsToRepeatedField(IDs $ids): RepeatedField
{
    if ($intIDs = $ids->getIntId()) {
        return $intIDs->getData();
    } elseif ($stringIDs = $ids->getStrId()) {
        return $stringIDs->getData();
    }

    throw new ParamException("IDs internal data type must be either string or int");
}