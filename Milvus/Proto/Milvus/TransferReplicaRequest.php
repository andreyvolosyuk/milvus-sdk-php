<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * transfer `replicaNum` replicas in `collectionID` from `source_resource_group` to `target_resource_group`
 *
 * Generated from protobuf message <code>milvus.proto.milvus.TransferReplicaRequest</code>
 */
class TransferReplicaRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * Generated from protobuf field <code>string source_resource_group = 2;</code>
     */
    protected $source_resource_group = '';
    /**
     * Generated from protobuf field <code>string target_resource_group = 3;</code>
     */
    protected $target_resource_group = '';
    /**
     * Generated from protobuf field <code>string collection_name = 4;</code>
     */
    protected $collection_name = '';
    /**
     * Generated from protobuf field <code>int64 num_replica = 5;</code>
     */
    protected $num_replica = 0;
    /**
     * Generated from protobuf field <code>string db_name = 6;</code>
     */
    protected $db_name = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *     @type string $source_resource_group
     *     @type string $target_resource_group
     *     @type string $collection_name
     *     @type int|string $num_replica
     *     @type string $db_name
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     * @return \Milvus\Proto\Common\MsgBase
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
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
     * Generated from protobuf field <code>string source_resource_group = 2;</code>
     * @return string
     */
    public function getSourceResourceGroup()
    {
        return $this->source_resource_group;
    }

    /**
     * Generated from protobuf field <code>string source_resource_group = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setSourceResourceGroup($var)
    {
        GPBUtil::checkString($var, True);
        $this->source_resource_group = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string target_resource_group = 3;</code>
     * @return string
     */
    public function getTargetResourceGroup()
    {
        return $this->target_resource_group;
    }

    /**
     * Generated from protobuf field <code>string target_resource_group = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setTargetResourceGroup($var)
    {
        GPBUtil::checkString($var, True);
        $this->target_resource_group = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string collection_name = 4;</code>
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collection_name;
    }

    /**
     * Generated from protobuf field <code>string collection_name = 4;</code>
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
     * Generated from protobuf field <code>int64 num_replica = 5;</code>
     * @return int|string
     */
    public function getNumReplica()
    {
        return $this->num_replica;
    }

    /**
     * Generated from protobuf field <code>int64 num_replica = 5;</code>
     * @param int|string $var
     * @return $this
     */
    public function setNumReplica($var)
    {
        GPBUtil::checkInt64($var);
        $this->num_replica = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string db_name = 6;</code>
     * @return string
     */
    public function getDbName()
    {
        return $this->db_name;
    }

    /**
     * Generated from protobuf field <code>string db_name = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setDbName($var)
    {
        GPBUtil::checkString($var, True);
        $this->db_name = $var;

        return $this;
    }

}

