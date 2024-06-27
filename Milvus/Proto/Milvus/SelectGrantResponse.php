<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.SelectGrantResponse</code>
 */
class SelectGrantResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Not useful for now
     *
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     */
    protected $status = null;
    /**
     * grant info array
     *
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.GrantEntity entities = 2;</code>
     */
    private $entities;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\Status $status
     *           Not useful for now
     *     @type \Milvus\Proto\Milvus\GrantEntity[]|\Google\Protobuf\Internal\RepeatedField $entities
     *           grant info array
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Not useful for now
     *
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     * @return \Milvus\Proto\Common\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Not useful for now
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
     * grant info array
     *
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.GrantEntity entities = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * grant info array
     *
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.GrantEntity entities = 2;</code>
     * @param \Milvus\Proto\Milvus\GrantEntity[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setEntities($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Milvus\GrantEntity::class);
        $this->entities = $arr;

        return $this;
    }

}
