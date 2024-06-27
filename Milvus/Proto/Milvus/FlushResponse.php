<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.FlushResponse</code>
 */
class FlushResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     */
    protected $status = null;
    /**
     * Generated from protobuf field <code>string db_name = 2;</code>
     */
    protected $db_name = '';
    /**
     * Generated from protobuf field <code>map<string, .milvus.proto.schema.LongArray> coll_segIDs = 3;</code>
     */
    private $coll_segIDs;
    /**
     * Generated from protobuf field <code>map<string, .milvus.proto.schema.LongArray> flush_coll_segIDs = 4;</code>
     */
    private $flush_coll_segIDs;
    /**
     * physical time for backup tool
     *
     * Generated from protobuf field <code>map<string, int64> coll_seal_times = 5;</code>
     */
    private $coll_seal_times;
    /**
     * hybrid ts for geting flush tate
     *
     * Generated from protobuf field <code>map<string, uint64> coll_flush_ts = 6;</code>
     */
    private $coll_flush_ts;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\Status $status
     *     @type string $db_name
     *     @type array|\Google\Protobuf\Internal\MapField $coll_segIDs
     *     @type array|\Google\Protobuf\Internal\MapField $flush_coll_segIDs
     *     @type array|\Google\Protobuf\Internal\MapField $coll_seal_times
     *           physical time for backup tool
     *     @type array|\Google\Protobuf\Internal\MapField $coll_flush_ts
     *           hybrid ts for geting flush tate
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
     * Generated from protobuf field <code>map<string, .milvus.proto.schema.LongArray> coll_segIDs = 3;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getCollSegIDs()
    {
        return $this->coll_segIDs;
    }

    /**
     * Generated from protobuf field <code>map<string, .milvus.proto.schema.LongArray> coll_segIDs = 3;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setCollSegIDs($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Schema\LongArray::class);
        $this->coll_segIDs = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<string, .milvus.proto.schema.LongArray> flush_coll_segIDs = 4;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getFlushCollSegIDs()
    {
        return $this->flush_coll_segIDs;
    }

    /**
     * Generated from protobuf field <code>map<string, .milvus.proto.schema.LongArray> flush_coll_segIDs = 4;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setFlushCollSegIDs($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Schema\LongArray::class);
        $this->flush_coll_segIDs = $arr;

        return $this;
    }

    /**
     * physical time for backup tool
     *
     * Generated from protobuf field <code>map<string, int64> coll_seal_times = 5;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getCollSealTimes()
    {
        return $this->coll_seal_times;
    }

    /**
     * physical time for backup tool
     *
     * Generated from protobuf field <code>map<string, int64> coll_seal_times = 5;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setCollSealTimes($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::INT64);
        $this->coll_seal_times = $arr;

        return $this;
    }

    /**
     * hybrid ts for geting flush tate
     *
     * Generated from protobuf field <code>map<string, uint64> coll_flush_ts = 6;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getCollFlushTs()
    {
        return $this->coll_flush_ts;
    }

    /**
     * hybrid ts for geting flush tate
     *
     * Generated from protobuf field <code>map<string, uint64> coll_flush_ts = 6;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setCollFlushTs($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::UINT64);
        $this->coll_flush_ts = $arr;

        return $this;
    }

}
