<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: schema.proto

namespace Milvus\Proto\Schema;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.schema.SearchResultData</code>
 */
class SearchResultData extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 num_queries = 1;</code>
     */
    protected $num_queries = 0;
    /**
     * Generated from protobuf field <code>int64 top_k = 2;</code>
     */
    protected $top_k = 0;
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.FieldData fields_data = 3;</code>
     */
    private $fields_data;
    /**
     * Generated from protobuf field <code>repeated float scores = 4;</code>
     */
    private $scores;
    /**
     * Generated from protobuf field <code>.milvus.proto.schema.IDs ids = 5;</code>
     */
    protected $ids = null;
    /**
     * Generated from protobuf field <code>repeated int64 topks = 6;</code>
     */
    private $topks;
    /**
     * Generated from protobuf field <code>repeated string output_fields = 7;</code>
     */
    private $output_fields;
    /**
     * Generated from protobuf field <code>.milvus.proto.schema.FieldData group_by_field_value = 8;</code>
     */
    protected $group_by_field_value = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $num_queries
     *     @type int|string $top_k
     *     @type \Milvus\Proto\Schema\FieldData[]|\Google\Protobuf\Internal\RepeatedField $fields_data
     *     @type float[]|\Google\Protobuf\Internal\RepeatedField $scores
     *     @type \Milvus\Proto\Schema\IDs $ids
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $topks
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $output_fields
     *     @type \Milvus\Proto\Schema\FieldData $group_by_field_value
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Schema::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 num_queries = 1;</code>
     * @return int|string
     */
    public function getNumQueries()
    {
        return $this->num_queries;
    }

    /**
     * Generated from protobuf field <code>int64 num_queries = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setNumQueries($var)
    {
        GPBUtil::checkInt64($var);
        $this->num_queries = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 top_k = 2;</code>
     * @return int|string
     */
    public function getTopK()
    {
        return $this->top_k;
    }

    /**
     * Generated from protobuf field <code>int64 top_k = 2;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTopK($var)
    {
        GPBUtil::checkInt64($var);
        $this->top_k = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.FieldData fields_data = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getFieldsData()
    {
        return $this->fields_data;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.FieldData fields_data = 3;</code>
     * @param \Milvus\Proto\Schema\FieldData[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setFieldsData($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Schema\FieldData::class);
        $this->fields_data = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated float scores = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Generated from protobuf field <code>repeated float scores = 4;</code>
     * @param float[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setScores($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::FLOAT);
        $this->scores = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.schema.IDs ids = 5;</code>
     * @return \Milvus\Proto\Schema\IDs
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.schema.IDs ids = 5;</code>
     * @param \Milvus\Proto\Schema\IDs $var
     * @return $this
     */
    public function setIds($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Schema\IDs::class);
        $this->ids = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated int64 topks = 6;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getTopks()
    {
        return $this->topks;
    }

    /**
     * Generated from protobuf field <code>repeated int64 topks = 6;</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setTopks($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT64);
        $this->topks = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string output_fields = 7;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getOutputFields()
    {
        return $this->output_fields;
    }

    /**
     * Generated from protobuf field <code>repeated string output_fields = 7;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setOutputFields($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->output_fields = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.schema.FieldData group_by_field_value = 8;</code>
     * @return \Milvus\Proto\Schema\FieldData
     */
    public function getGroupByFieldValue()
    {
        return $this->group_by_field_value;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.schema.FieldData group_by_field_value = 8;</code>
     * @param \Milvus\Proto\Schema\FieldData $var
     * @return $this
     */
    public function setGroupByFieldValue($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Schema\FieldData::class);
        $this->group_by_field_value = $var;

        return $this;
    }

}
