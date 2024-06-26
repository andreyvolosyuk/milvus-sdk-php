<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Return basic collection infos.
 *
 * Generated from protobuf message <code>milvus.proto.milvus.ShowCollectionsResponse</code>
 */
class ShowCollectionsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Contain error_code and reason
     *
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     */
    protected $status = null;
    /**
     * Collection name array
     *
     * Generated from protobuf field <code>repeated string collection_names = 2;</code>
     */
    private $collection_names;
    /**
     * Collection Id array
     *
     * Generated from protobuf field <code>repeated int64 collection_ids = 3;</code>
     */
    private $collection_ids;
    /**
     * Hybrid timestamps in milvus
     *
     * Generated from protobuf field <code>repeated uint64 created_timestamps = 4;</code>
     */
    private $created_timestamps;
    /**
     * The utc timestamp calculated by created_timestamp
     *
     * Generated from protobuf field <code>repeated uint64 created_utc_timestamps = 5;</code>
     */
    private $created_utc_timestamps;
    /**
     * Load percentage on querynode when type is InMemory
     * Deprecated: use GetLoadingProgress rpc instead
     *
     * Generated from protobuf field <code>repeated int64 inMemory_percentages = 6 [deprecated = true];</code>
     */
    private $inMemory_percentages;
    /**
     * Indicate whether query service is available
     *
     * Generated from protobuf field <code>repeated bool query_service_available = 7;</code>
     */
    private $query_service_available;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\Status $status
     *           Contain error_code and reason
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $collection_names
     *           Collection name array
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $collection_ids
     *           Collection Id array
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $created_timestamps
     *           Hybrid timestamps in milvus
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $created_utc_timestamps
     *           The utc timestamp calculated by created_timestamp
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $inMemory_percentages
     *           Load percentage on querynode when type is InMemory
     *           Deprecated: use GetLoadingProgress rpc instead
     *     @type bool[]|\Google\Protobuf\Internal\RepeatedField $query_service_available
     *           Indicate whether query service is available
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Contain error_code and reason
     *
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     * @return \Milvus\Proto\Common\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Contain error_code and reason
     *
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     * @param \Milvus\Proto\Common\Status $var
     * @return $this
     */
    public function setStatus($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Common\Status::class);
        $this->status = $var;

        return $this;
    }

    /**
     * Collection name array
     *
     * Generated from protobuf field <code>repeated string collection_names = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getCollectionNames()
    {
        return $this->collection_names;
    }

    /**
     * Collection name array
     *
     * Generated from protobuf field <code>repeated string collection_names = 2;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setCollectionNames($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->collection_names = $arr;

        return $this;
    }

    /**
     * Collection Id array
     *
     * Generated from protobuf field <code>repeated int64 collection_ids = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getCollectionIds()
    {
        return $this->collection_ids;
    }

    /**
     * Collection Id array
     *
     * Generated from protobuf field <code>repeated int64 collection_ids = 3;</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setCollectionIds($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT64);
        $this->collection_ids = $arr;

        return $this;
    }

    /**
     * Hybrid timestamps in milvus
     *
     * Generated from protobuf field <code>repeated uint64 created_timestamps = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getCreatedTimestamps()
    {
        return $this->created_timestamps;
    }

    /**
     * Hybrid timestamps in milvus
     *
     * Generated from protobuf field <code>repeated uint64 created_timestamps = 4;</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setCreatedTimestamps($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::UINT64);
        $this->created_timestamps = $arr;

        return $this;
    }

    /**
     * The utc timestamp calculated by created_timestamp
     *
     * Generated from protobuf field <code>repeated uint64 created_utc_timestamps = 5;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getCreatedUtcTimestamps()
    {
        return $this->created_utc_timestamps;
    }

    /**
     * The utc timestamp calculated by created_timestamp
     *
     * Generated from protobuf field <code>repeated uint64 created_utc_timestamps = 5;</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setCreatedUtcTimestamps($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::UINT64);
        $this->created_utc_timestamps = $arr;

        return $this;
    }

    /**
     * Load percentage on querynode when type is InMemory
     * Deprecated: use GetLoadingProgress rpc instead
     *
     * Generated from protobuf field <code>repeated int64 inMemory_percentages = 6 [deprecated = true];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getInMemoryPercentages()
    {
        return $this->inMemory_percentages;
    }

    /**
     * Load percentage on querynode when type is InMemory
     * Deprecated: use GetLoadingProgress rpc instead
     *
     * Generated from protobuf field <code>repeated int64 inMemory_percentages = 6 [deprecated = true];</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setInMemoryPercentages($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT64);
        $this->inMemory_percentages = $arr;

        return $this;
    }

    /**
     * Indicate whether query service is available
     *
     * Generated from protobuf field <code>repeated bool query_service_available = 7;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getQueryServiceAvailable()
    {
        return $this->query_service_available;
    }

    /**
     * Indicate whether query service is available
     *
     * Generated from protobuf field <code>repeated bool query_service_available = 7;</code>
     * @param bool[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setQueryServiceAvailable($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::BOOL);
        $this->query_service_available = $arr;

        return $this;
    }

}

