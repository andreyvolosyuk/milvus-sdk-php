<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.Hits</code>
 */
class Hits extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated int64 IDs = 1;</code>
     */
    private $IDs;
    /**
     * Generated from protobuf field <code>repeated bytes row_data = 2;</code>
     */
    private $row_data;
    /**
     * Generated from protobuf field <code>repeated float scores = 3;</code>
     */
    private $scores;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $IDs
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $row_data
     *     @type float[]|\Google\Protobuf\Internal\RepeatedField $scores
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated int64 IDs = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getIDs()
    {
        return $this->IDs;
    }

    /**
     * Generated from protobuf field <code>repeated int64 IDs = 1;</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setIDs($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT64);
        $this->IDs = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated bytes row_data = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getRowData()
    {
        return $this->row_data;
    }

    /**
     * Generated from protobuf field <code>repeated bytes row_data = 2;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setRowData($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::BYTES);
        $this->row_data = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated float scores = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Generated from protobuf field <code>repeated float scores = 3;</code>
     * @param float[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setScores($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::FLOAT);
        $this->scores = $arr;

        return $this;
    }

}
