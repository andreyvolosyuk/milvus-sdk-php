<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: common.proto

namespace Milvus\Proto\Common;

use UnexpectedValueException;

/**
 * Protobuf type <code>milvus.proto.common.PlaceholderType</code>
 */
class PlaceholderType
{
    /**
     * Generated from protobuf enum <code>None = 0;</code>
     */
    const None = 0;
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
    /**
     * Generated from protobuf enum <code>Int64 = 5;</code>
     */
    const Int64 = 5;
    /**
     * Generated from protobuf enum <code>VarChar = 21;</code>
     */
    const VarChar = 21;

    private static $valueToName = [
        self::None => 'None',
        self::BinaryVector => 'BinaryVector',
        self::FloatVector => 'FloatVector',
        self::Float16Vector => 'Float16Vector',
        self::BFloat16Vector => 'BFloat16Vector',
        self::Int64 => 'Int64',
        self::VarChar => 'VarChar',
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

