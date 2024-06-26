<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.GetQuerySegmentInfoRequest</code>
 */
class GetQuerySegmentInfoRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * must
     *
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * Generated from protobuf field <code>string dbName = 2;</code>
     */
    protected $dbName = '';
    /**
     * must
     *
     * Generated from protobuf field <code>string collectionName = 3;</code>
     */
    protected $collectionName = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *           must
     *     @type string $dbName
     *     @type string $collectionName
     *           must
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * must
     *
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     * @return \Milvus\Proto\Common\MsgBase
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * must
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
     * Generated from protobuf field <code>string dbName = 2;</code>
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * Generated from protobuf field <code>string dbName = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDbName($var)
    {
        GPBUtil::checkString($var, True);
        $this->dbName = $var;

        return $this;
    }

    /**
     * must
     *
     * Generated from protobuf field <code>string collectionName = 3;</code>
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * must
     *
     * Generated from protobuf field <code>string collectionName = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setCollectionName($var)
    {
        GPBUtil::checkString($var, True);
        $this->collectionName = $var;

        return $this;
    }

}

