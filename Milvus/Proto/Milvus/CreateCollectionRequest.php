<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 **
 * Create collection in milvus
 *
 * Generated from protobuf message <code>milvus.proto.milvus.CreateCollectionRequest</code>
 */
class CreateCollectionRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Not useful for now
     *
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * Generated from protobuf field <code>string db_name = 2;</code>
     */
    protected $db_name = '';
    /**
     * The unique collection name in milvus.(Required)
     *
     * Generated from protobuf field <code>string collection_name = 3;</code>
     */
    protected $collection_name = '';
    /**
     * The serialized `schema.CollectionSchema`(Required)
     *
     * Generated from protobuf field <code>bytes schema = 4;</code>
     */
    protected $schema = '';
    /**
     * Once set, no modification is allowed (Optional)
     * https://github.com/milvus-io/milvus/issues/6690
     *
     * Generated from protobuf field <code>int32 shards_num = 5;</code>
     */
    protected $shards_num = 0;
    /**
     * The consistency level that the collection used, modification is not supported now.
     *
     * Generated from protobuf field <code>.milvus.proto.common.ConsistencyLevel consistency_level = 6;</code>
     */
    protected $consistency_level = 0;
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.common.KeyValuePair properties = 7;</code>
     */
    private $properties;
    /**
     * num of default physical partitions, only used in partition key mode and changes are not supported
     *
     * Generated from protobuf field <code>int64 num_partitions = 8;</code>
     */
    protected $num_partitions = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *           Not useful for now
     *     @type string $db_name
     *     @type string $collection_name
     *           The unique collection name in milvus.(Required)
     *     @type string $schema
     *           The serialized `schema.CollectionSchema`(Required)
     *     @type int $shards_num
     *           Once set, no modification is allowed (Optional)
     *           https://github.com/milvus-io/milvus/issues/6690
     *     @type int $consistency_level
     *           The consistency level that the collection used, modification is not supported now.
     *     @type \Milvus\Proto\Common\KeyValuePair[]|\Google\Protobuf\Internal\RepeatedField $properties
     *     @type int|string $num_partitions
     *           num of default physical partitions, only used in partition key mode and changes are not supported
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Not useful for now
     *
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     * @return \Milvus\Proto\Common\MsgBase
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Not useful for now
     *
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     * @param \Milvus\Proto\Common\MsgBase $var
     * @return $this
     */
    public function setBase($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Common\MsgBase::class);
        $this->base = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string db_name = 2;</code>
     * @return string
     */
    public function getDbName()
    {
        return $this->db_name;
    }

    /**
     * Generated from protobuf field <code>string db_name = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDbName($var)
    {
        GPBUtil::checkString($var, True);
        $this->db_name = $var;

        return $this;
    }

    /**
     * The unique collection name in milvus.(Required)
     *
     * Generated from protobuf field <code>string collection_name = 3;</code>
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collection_name;
    }

    /**
     * The unique collection name in milvus.(Required)
     *
     * Generated from protobuf field <code>string collection_name = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setCollectionName($var)
    {
        GPBUtil::checkString($var, True);
        $this->collection_name = $var;

        return $this;
    }

    /**
     * The serialized `schema.CollectionSchema`(Required)
     *
     * Generated from protobuf field <code>bytes schema = 4;</code>
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * The serialized `schema.CollectionSchema`(Required)
     *
     * Generated from protobuf field <code>bytes schema = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setSchema($var)
    {
        GPBUtil::checkString($var, False);
        $this->schema = $var;

        return $this;
    }

    /**
     * Once set, no modification is allowed (Optional)
     * https://github.com/milvus-io/milvus/issues/6690
     *
     * Generated from protobuf field <code>int32 shards_num = 5;</code>
     * @return int
     */
    public function getShardsNum()
    {
        return $this->shards_num;
    }

    /**
     * Once set, no modification is allowed (Optional)
     * https://github.com/milvus-io/milvus/issues/6690
     *
     * Generated from protobuf field <code>int32 shards_num = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setShardsNum($var)
    {
        GPBUtil::checkInt32($var);
        $this->shards_num = $var;

        return $this;
    }

    /**
     * The consistency level that the collection used, modification is not supported now.
     *
     * Generated from protobuf field <code>.milvus.proto.common.ConsistencyLevel consistency_level = 6;</code>
     * @return int
     */
    public function getConsistencyLevel()
    {
        return $this->consistency_level;
    }

    /**
     * The consistency level that the collection used, modification is not supported now.
     *
     * Generated from protobuf field <code>.milvus.proto.common.ConsistencyLevel consistency_level = 6;</code>
     * @param int $var
     * @return $this
     */
    public function setConsistencyLevel($var)
    {
        GPBUtil::checkEnum($var, \Milvus\Proto\Common\ConsistencyLevel::class);
        $this->consistency_level = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.common.KeyValuePair properties = 7;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.common.KeyValuePair properties = 7;</code>
     * @param \Milvus\Proto\Common\KeyValuePair[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setProperties($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Common\KeyValuePair::class);
        $this->properties = $arr;

        return $this;
    }

    /**
     * num of default physical partitions, only used in partition key mode and changes are not supported
     *
     * Generated from protobuf field <code>int64 num_partitions = 8;</code>
     * @return int|string
     */
    public function getNumPartitions()
    {
        return $this->num_partitions;
    }

    /**
     * num of default physical partitions, only used in partition key mode and changes are not supported
     *
     * Generated from protobuf field <code>int64 num_partitions = 8;</code>
     * @param int|string $var
     * @return $this
     */
    public function setNumPartitions($var)
    {
        GPBUtil::checkInt64($var);
        $this->num_partitions = $var;

        return $this;
    }

}

