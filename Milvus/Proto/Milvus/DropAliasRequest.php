<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.DropAliasRequest</code>
 */
class DropAliasRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * Generated from protobuf field <code>string db_name = 2;</code>
     */
    protected $db_name = '';
    /**
     * Generated from protobuf field <code>string alias = 3;</code>
     */
    protected $alias = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *     @type string $db_name
     *     @type string $alias
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
     * Generated from protobuf field <code>string alias = 3;</code>
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Generated from protobuf field <code>string alias = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setAlias($var)
    {
        GPBUtil::checkString($var, True);
        $this->alias = $var;

        return $this;
    }

}

