<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.RoleResult</code>
 */
class RoleResult extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.RoleEntity role = 1;</code>
     */
    protected $role = null;
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.UserEntity users = 2;</code>
     */
    private $users;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Milvus\RoleEntity $role
     *     @type \Milvus\Proto\Milvus\UserEntity[]|\Google\Protobuf\Internal\RepeatedField $users
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Milvus::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.RoleEntity role = 1;</code>
     * @return \Milvus\Proto\Milvus\RoleEntity
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Generated from protobuf field <code>.milvus.proto.milvus.RoleEntity role = 1;</code>
     * @param \Milvus\Proto\Milvus\RoleEntity $var
     * @return $this
     */
    public function setRole($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Milvus\RoleEntity::class);
        $this->role = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.UserEntity users = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.milvus.UserEntity users = 2;</code>
     * @param \Milvus\Proto\Milvus\UserEntity[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setUsers($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Milvus\UserEntity::class);
        $this->users = $arr;

        return $this;
    }

}

