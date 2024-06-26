<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.SelectUserRequest</code>
 */
class SelectUserRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Not useful for now
     *
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * user
     *
     * Generated from protobuf field <code>.milvus.proto.milvus.UserEntity user = 2;</code>
     */
    protected $user = null;
    /**
     * include user info
     *
     * Generated from protobuf field <code>bool include_role_info = 3;</code>
     */
    protected $include_role_info = false;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *           Not useful for now
     *     @type \Milvus\Proto\Milvus\UserEntity $user
     *           user
     *     @type bool $include_role_info
     *           include user info
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
     * user
     *
     * Generated from protobuf field <code>.milvus.proto.milvus.UserEntity user = 2;</code>
     * @return \Milvus\Proto\Milvus\UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * user
     *
     * Generated from protobuf field <code>.milvus.proto.milvus.UserEntity user = 2;</code>
     * @param \Milvus\Proto\Milvus\UserEntity $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Milvus\UserEntity::class);
        $this->user = $var;

        return $this;
    }

    /**
     * include user info
     *
     * Generated from protobuf field <code>bool include_role_info = 3;</code>
     * @return bool
     */
    public function getIncludeRoleInfo()
    {
        return $this->include_role_info;
    }

    /**
     * include user info
     *
     * Generated from protobuf field <code>bool include_role_info = 3;</code>
     * @param bool $var
     * @return $this
     */
    public function setIncludeRoleInfo($var)
    {
        GPBUtil::checkBool($var);
        $this->include_role_info = $var;

        return $this;
    }

}

