<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.GetReplicasResponse</code>
 */
class GetReplicasResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     */
    protected $status = null;
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.ReplicaInfo replicas = 2;</code>
     */
    private $replicas;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\Status $status
     *     @type \Milvus\Proto\Milvus\ReplicaInfo[]|\Google\Protobuf\Internal\RepeatedField $replicas
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     * @return \Milvus\Proto\Common\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
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
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.ReplicaInfo replicas = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getReplicas()
    {
        return $this->replicas;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.ReplicaInfo replicas = 2;</code>
     * @param \Milvus\Proto\Milvus\ReplicaInfo[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setReplicas($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Milvus\ReplicaInfo::class);
        $this->replicas = $arr;

        return $this;
    }

}

