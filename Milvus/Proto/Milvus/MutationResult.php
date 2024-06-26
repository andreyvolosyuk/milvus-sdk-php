<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.MutationResult</code>
 */
class MutationResult extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     */
    protected $status = null;
    /**
     * required for insert, delete, upsert
     *
     * Generated from protobuf field <code>.milvus.proto.schema.IDs IDs = 2;</code>
     */
    protected $IDs = null;
    /**
     * error indexes indicate
     *
     * Generated from protobuf field <code>repeated uint32 succ_index = 3;</code>
     */
    private $succ_index;
    /**
     * error indexes indicate
     *
     * Generated from protobuf field <code>repeated uint32 err_index = 4;</code>
     */
    private $err_index;
    /**
     * Generated from protobuf field <code>bool acknowledged = 5;</code>
     */
    protected $acknowledged = false;
    /**
     * Generated from protobuf field <code>int64 insert_cnt = 6;</code>
     */
    protected $insert_cnt = 0;
    /**
     * Generated from protobuf field <code>int64 delete_cnt = 7;</code>
     */
    protected $delete_cnt = 0;
    /**
     * Generated from protobuf field <code>int64 upsert_cnt = 8;</code>
     */
    protected $upsert_cnt = 0;
    /**
     * Generated from protobuf field <code>uint64 timestamp = 9;</code>
     */
    protected $timestamp = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\Status $status
     *     @type \Milvus\Proto\Schema\IDs $IDs
     *           required for insert, delete, upsert
     *     @type int[]|\Google\Protobuf\Internal\RepeatedField $succ_index
     *           error indexes indicate
     *     @type int[]|\Google\Protobuf\Internal\RepeatedField $err_index
     *           error indexes indicate
     *     @type bool $acknowledged
     *     @type int|string $insert_cnt
     *     @type int|string $delete_cnt
     *     @type int|string $upsert_cnt
     *     @type int|string $timestamp
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
     * required for insert, delete, upsert
     *
     * Generated from protobuf field <code>.milvus.proto.schema.IDs IDs = 2;</code>
     * @return \Milvus\Proto\Schema\IDs
     */
    public function getIDs()
    {
        return $this->IDs;
    }

    /**
     * required for insert, delete, upsert
     *
     * Generated from protobuf field <code>.milvus.proto.schema.IDs IDs = 2;</code>
     * @param \Milvus\Proto\Schema\IDs $var
     * @return $this
     */
    public function setIDs($var)
    {
        GPBUtil::checkMessage($var, \Milvus\Proto\Schema\IDs::class);
        $this->IDs = $var;

        return $this;
    }

    /**
     * error indexes indicate
     *
     * Generated from protobuf field <code>repeated uint32 succ_index = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSuccIndex()
    {
        return $this->succ_index;
    }

    /**
     * error indexes indicate
     *
     * Generated from protobuf field <code>repeated uint32 succ_index = 3;</code>
     * @param int[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSuccIndex($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::UINT32);
        $this->succ_index = $arr;

        return $this;
    }

    /**
     * error indexes indicate
     *
     * Generated from protobuf field <code>repeated uint32 err_index = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getErrIndex()
    {
        return $this->err_index;
    }

    /**
     * error indexes indicate
     *
     * Generated from protobuf field <code>repeated uint32 err_index = 4;</code>
     * @param int[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setErrIndex($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::UINT32);
        $this->err_index = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool acknowledged = 5;</code>
     * @return bool
     */
    public function getAcknowledged()
    {
        return $this->acknowledged;
    }

    /**
     * Generated from protobuf field <code>bool acknowledged = 5;</code>
     * @param bool $var
     * @return $this
     */
    public function setAcknowledged($var)
    {
        GPBUtil::checkBool($var);
        $this->acknowledged = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 insert_cnt = 6;</code>
     * @return int|string
     */
    public function getInsertCnt()
    {
        return $this->insert_cnt;
    }

    /**
     * Generated from protobuf field <code>int64 insert_cnt = 6;</code>
     * @param int|string $var
     * @return $this
     */
    public function setInsertCnt($var)
    {
        GPBUtil::checkInt64($var);
        $this->insert_cnt = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 delete_cnt = 7;</code>
     * @return int|string
     */
    public function getDeleteCnt()
    {
        return $this->delete_cnt;
    }

    /**
     * Generated from protobuf field <code>int64 delete_cnt = 7;</code>
     * @param int|string $var
     * @return $this
     */
    public function setDeleteCnt($var)
    {
        GPBUtil::checkInt64($var);
        $this->delete_cnt = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 upsert_cnt = 8;</code>
     * @return int|string
     */
    public function getUpsertCnt()
    {
        return $this->upsert_cnt;
    }

    /**
     * Generated from protobuf field <code>int64 upsert_cnt = 8;</code>
     * @param int|string $var
     * @return $this
     */
    public function setUpsertCnt($var)
    {
        GPBUtil::checkInt64($var);
        $this->upsert_cnt = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 timestamp = 9;</code>
     * @return int|string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Generated from protobuf field <code>uint64 timestamp = 9;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimestamp($var)
    {
        GPBUtil::checkUint64($var);
        $this->timestamp = $var;

        return $this;
    }

}

