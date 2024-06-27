<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: schema.proto

namespace Milvus\Proto\Schema;

use UnexpectedValueException;

/**
 **
 * &#64;brief Field data type
 *
 * Protobuf type <code>milvus.proto.schema.DataType</code>
 */
class DataType
{
    /**
     * Generated from protobuf enum <code>None = 0;</code>
     */
    const None = 0;
    /**
     * Generated from protobuf enum <code>Bool = 1;</code>
     */
    const Bool = 1;
    /**
     * Generated from protobuf enum <code>Int8 = 2;</code>
     */
    const Int8 = 2;
    /**
     * Generated from protobuf enum <code>Int16 = 3;</code>
     */
    const Int16 = 3;
    /**
     * Generated from protobuf enum <code>Int32 = 4;</code>
     */
    const Int32 = 4;
    /**
     * Generated from protobuf enum <code>Int64 = 5;</code>
     */
    const Int64 = 5;
    /**
     * Generated from protobuf enum <code>Float = 10;</code>
     */
    const Float = 10;
    /**
     * Generated from protobuf enum <code>Double = 11;</code>
     */
    const Double = 11;
    /**
     * Generated from protobuf enum <code>String = 20;</code>
     */
    const String = 20;
    /**
     * variable-length strings with a specified maximum length
     *
     * Generated from protobuf enum <code>VarChar = 21;</code>
     */
    const VarChar = 21;
    /**
     * Generated from protobuf enum <code>Array = 22;</code>
     */
    const PBArray = 22;
    /**
     * Generated from protobuf enum <code>JSON = 23;</code>
     */
    const JSON = 23;
    /**
     * Generated from protobuf enum <code>BinaryVector = 100;</code>
     */
    const BinaryVector = 100;
    /**
     * Generated from protobuf enum <code>FloatVector = 101;</code>
     */
    const FloatVector = 101;
    /**
     * Generated from protobuf enum <code>Float16Vector = 102;</code>
     */
    const Float16Vector = 102;
    /**
     * Generated from protobuf enum <code>BFloat16Vector = 103;</code>
     */
    const BFloat16Vector = 103;

    private static $valueToName = [
        self::None => 'None',
        self::Bool => 'Bool',
        self::Int8 => 'Int8',
        self::Int16 => 'Int16',
        self::Int32 => 'Int32',
        self::Int64 => 'Int64',
        self::Float => 'Float',
        self::Double => 'Double',
        self::String => 'String',
        self::VarChar => 'VarChar',
        self::PBArray => 'PBArray',
        self::JSON => 'JSON',
        self::BinaryVector => 'BinaryVector',
        self::FloatVector => 'FloatVector',
        self::Float16Vector => 'Float16Vector',
        self::BFloat16Vector => 'BFloat16Vector',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

