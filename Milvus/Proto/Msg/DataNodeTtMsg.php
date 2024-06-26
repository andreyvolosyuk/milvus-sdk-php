<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: msg.proto

namespace Milvus\Proto\Msg;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.msg.DataNodeTtMsg</code>
 */
class DataNodeTtMsg extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * Generated from protobuf field <code>string channel_name = 2;</code>
     */
    protected $channel_name = '';
    /**
     * Generated from protobuf field <code>uint64 timestamp = 3;</code>
     */
    protected $timestamp = 0;
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.common.SegmentStats segments_stats = 4;</code>
     */
    private $segments_stats;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *     @type string $channel_name
     *     @type int|string $timestamp
     *     @type \Milvus\Proto\Common\SegmentStats[]|\Google\Protobuf\Internal\RepeatedField $segments_stats
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Msg::initOnce();
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
     * Generated from protobuf field <code>string channel_name = 2;</code>
     * @return string
     */
    public function getChannelName()
    {
        return $this->channel_name;
    }

    /**
     * Generated from protobuf field <code>string channel_name = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setChannelName($var)
    {
        GPBUtil::checkString($var, True);
        $this->channel_name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 timestamp = 3;</code>
     * @return int|string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Generated from protobuf field <code>uint64 timestamp = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimestamp($var)
    {
        GPBUtil::checkUint64($var);
        $this->timestamp = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.common.SegmentStats segments_stats = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSegmentsStats()
    {
        return $this->segments_stats;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.common.SegmentStats segments_stats = 4;</code>
     * @param \Milvus\Proto\Common\SegmentStats[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSegmentsStats($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Common\SegmentStats::class);
        $this->segments_stats = $arr;

        return $this;
    }

}

