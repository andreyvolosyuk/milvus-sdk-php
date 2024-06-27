<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: common.proto

namespace Milvus\Proto\Common;

use UnexpectedValueException;

/**
 * Protobuf type <code>milvus.proto.common.LoadState</code>
 */
class LoadState
{
    /**
     * Generated from protobuf enum <code>LoadStateNotExist = 0;</code>
     */
    const LoadStateNotExist = 0;
    /**
     * Generated from protobuf enum <code>LoadStateNotLoad = 1;</code>
     */
    const LoadStateNotLoad = 1;
    /**
     * Generated from protobuf enum <code>LoadStateLoading = 2;</code>
     */
    const LoadStateLoading = 2;
    /**
     * Generated from protobuf enum <code>LoadStateLoaded = 3;</code>
     */
    const LoadStateLoaded = 3;

    private static $valueToName = [
        self::LoadStateNotExist => 'LoadStateNotExist',
        self::LoadStateNotLoad => 'LoadStateNotLoad',
        self::LoadStateLoading => 'LoadStateLoading',
        self::LoadStateLoaded => 'LoadStateLoaded',
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
