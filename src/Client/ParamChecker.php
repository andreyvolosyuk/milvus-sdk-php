<?php


namespace Volosyuk\MilvusPhp\Client;

use Milvus\Proto\Milvus\ShowType;
use Milvus\Proto\Schema\DataType;
use UnexpectedValueException;
use Volosyuk\MilvusPhp\Exceptions\ParamException;

class ParamChecker
{
    /**
     * @param array $params
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function checkArray(array $params)
    {
        foreach ($params as $paramName => $paramValue) {
            if (!is_string($paramName)) {
                throw new ParamException(sprintf("Param name %s must be a string", $paramName));
            }

            $methodName = sprintf("isLegal%s", ucfirst($paramName));
            if (!method_exists(static::class, $methodName)) {
                throw new ParamException(sprintf("Param checker for attribute %s does not exist", $paramName));
            }

            call_user_func([static::class, $methodName], $paramValue);
        }
    }

    /**
     * @param int $limit
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalLimit(int $limit)
    {
        if ($limit <= 0) {
            throw new ParamException("Limit must be greater that 0");
        }
    }

    /**
     * @param int $roundDecimal
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalRoundDecimal(int $roundDecimal = -1)
    {
        if ($roundDecimal < -1 or $roundDecimal > 6) {
            throw new ParamException("Round decimal must be in [-1; 6] interval");
        }
     }

    /**
     * @param string $collectionName
     *
     * @throws ParamException
     */
    public static function isLegalCollectionName(string $collectionName)
    {
        if (!$collectionName) {
            throw new ParamException("Illegal collection name $collectionName");
        }
    }

    /**
     * @throws ParamException
     */
    public static function isLegalShowType(int $showType)
    {
        try {
            ShowType::name($showType);
        } catch (UnexpectedValueException $e) {
            throw new ParamException($e);
        }
    }

    /**
     * @param string $annsField
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalAnnsField(string $annsField)
    {
        if (!$annsField) {
            throw new ParamException("Anns field must not be empty");
        }
    }

    /**
     * @param array $data
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalSearchData(array $data)
    {
        foreach ($data as $vector) {
            if (!is_array($vector)) { # todo make compatible with vectors
                throw new ParamException("Search data item must be an array");
            }
        }
    }

    /**
     * @param string $partitionName
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalPartitionName(string $partitionName)
    {
        if (!$partitionName) {
            throw new ParamException("Partition name must not be empty");
        }
    }

    /**
     * @param array $partitionNames
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalPartitionNameArray(array $partitionNames = [])
    {
        foreach ($partitionNames as $partitionName) {
            static::isLegalPartitionName($partitionName);
        }
    }

    /**
     * @param string $fieldName
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalFieldName(string $fieldName)
    {
        if (!$fieldName) {
            throw new ParamException("Field name must not be empty");
        }
    }

    /**
     * @param array $outputFields
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalOutputFields(array $outputFields = [])
    {
        foreach ($outputFields as $outputField) {
            static::isLegalFieldName($outputField);
        }
    }

    /**
     * @param int $travelTimestamp
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalTravelTimestamp(int $travelTimestamp = 0)
    {
        if ($travelTimestamp < 0) {
            throw new ParamException("Travel timestamp must be greater or equal to zero");
        }
    }

    /**
     * @param int $guaranteeTimestamp
     *
     * @return void
     *
     * @throws ParamException
     */
    public static function isLegalGuaranteeTimestamp(int $guaranteeTimestamp = 0)
    {
        if ($guaranteeTimestamp < 0) {
            throw new ParamException("Guarantee timestamp must be greater or equal to zero");
        }
    }

    /**
     * @param int $type
     *
     * @throws ParamException
     */
    public static function isLegalDataType(int $type)
    {
        try {
            DataType::name($type);
        } catch (UnexpectedValueException $e) {
            throw new ParamException($e);
        }
    }

    /**
     * @param string $roleName
     *
     * @throws ParamException
     */
    public static function isLegalRoleName(string $roleName)
    {
        if (!static::isLegalString($roleName)) {
            throw new ParamException("Role name must not be empty");
        }
    }

    /**
     * @param string $object
     *
     * @throws ParamException
     */
    public static function isLegalObject(string $object)
    {
        if (!static::isLegalString($object)) {
            throw new ParamException("Object must not be empty");
        }
    }

    /**
     * @param string $objectName
     *
     * @throws ParamException
     */
    public static function isLegalObjectName(string $objectName)
    {
        if (!static::isLegalString($objectName)) {
            throw new ParamException("Object name must not be empty");
        }
    }

    /**
     * @param string $privilege
     *
     * @throws ParamException
     */
    public static function isLegalPrivilege(string $privilege)
    {
        if (!static::isLegalString($privilege)) {
            throw new ParamException("Privilege name must not be empty");
        }
    }

    /**
     * @param string $userName
     *
     * @throws ParamException
     */
    public static function isLegalUserName(string $userName)
    {
        if (!static::isLegalString($userName)) {
            throw new ParamException("User name must not be empty");
        }
    }

    /**
     * @param string $item
     *
     * @return bool
     */
    private static function isLegalString(string $item): bool
    {
        return boolval($item);
    }
}
