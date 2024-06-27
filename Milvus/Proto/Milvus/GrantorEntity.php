<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.GrantorEntity</code>
 */
class GrantorEntity extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.UserEntity user = 1;</code>
     */
    protected $user = null;
    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.PrivilegeEntity privilege = 2;</code>
     */
    protected $privilege = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Milvus\UserEntity $user
     *     @type \Milvus\Proto\Milvus\PrivilegeEntity $privilege
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.UserEntity user = 1;</code>
     * @return \Milvus\Proto\Milvus\UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.UserEntity user = 1;</code>
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
     * Generated from protobuf field <code>.milvus.proto.milvus.PrivilegeEntity privilege = 2;</code>
     * @return \Milvus\Proto\Milvus\PrivilegeEntity
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.PrivilegeEntity privilege = 2;</code>
     * @param \Milvus\Proto\Milvus\PrivilegeEntity $var
     * @return $this
     */
    public function setPrivilege($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Milvus\PrivilegeEntity::class);
        $this->privilege = $var;

        return $this;
    }

}

