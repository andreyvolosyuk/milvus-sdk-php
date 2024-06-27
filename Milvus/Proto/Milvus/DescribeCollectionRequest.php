<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 **
 * Get collection meta datas like: schema, collectionID, shards number ...
 *
 * Generated from protobuf message <code>milvus.proto.milvus.DescribeCollectionRequest</code>
 */
class DescribeCollectionRequest extends \Google\Protobuf\Internal\Message
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
     * The collection name you want to describe, you can pass collection_name or collectionID
     *
     * Generated from protobuf field <code>string collection_name = 3;</code>
     */
    protected $collection_name = '';
    /**
     * The collection ID you want to describe
     *
     * Generated from protobuf field <code>int64 collectionID = 4;</code>
     */
    protected $collectionID = 0;
    /**
     * If time_stamp is not zero, will describe collection success when time_stamp >= created collection timestamp, otherwise will throw error.
     *
     * Generated from protobuf field <code>uint64 time_stamp = 5;</code>
     */
    protected $time_stamp = 0;

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
     *           The collection name you want to describe, you can pass collection_name or collectionID
     *     @type int|string $collectionID
     *           The collection ID you want to describe
     *     @type int|string $time_stamp
     *           If time_stamp is not zero, will describe collection success when time_stamp >= created collection timestamp, otherwise will throw error.
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
     * The collection name you want to describe, you can pass collection_name or collectionID
     *
     * Generated from protobuf field <code>string collection_name = 3;</code>
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collection_name;
    }

    /**
     * The collection name you want to describe, you can pass collection_name or collectionID
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
     * The collection ID you want to describe
     *
     * Generated from protobuf field <code>int64 collectionID = 4;</code>
     * @return int|string
     */
    public function getCollectionID()
    {
        return $this->collectionID;
    }

    /**
     * The collection ID you want to describe
     *
     * Generated from protobuf field <code>int64 collectionID = 4;</code>
     * @param int|string $var
     * @return $this
     */
    public function setCollectionID($var)
    {
        GPBUtil::checkInt64($var);
        $this->collectionID = $var;

        return $this;
    }

    /**
     * If time_stamp is not zero, will describe collection success when time_stamp >= created collection timestamp, otherwise will throw error.
     *
     * Generated from protobuf field <code>uint64 time_stamp = 5;</code>
     * @return int|string
     */
    public function getTimeStamp()
    {
        return $this->time_stamp;
    }

    /**
     * If time_stamp is not zero, will describe collection success when time_stamp >= created collection timestamp, otherwise will throw error.
     *
     * Generated from protobuf field <code>uint64 time_stamp = 5;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimeStamp($var)
    {
        GPBUtil::checkUint64($var);
        $this->time_stamp = $var;

        return $this;
    }

}
