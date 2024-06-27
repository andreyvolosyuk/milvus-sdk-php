<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: feder.proto

namespace Milvus\Proto\Feder;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.feder.DescribeSegmentIndexDataRequest</code>
 */
class DescribeSegmentIndexDataRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.MsgBase base = 1;</code>
     */
    protected $base = null;
    /**
     * Generated from protobuf field <code>string collection_name = 2;</code>
     */
    protected $collection_name = '';
    /**
     * Generated from protobuf field <code>string index_name = 3;</code>
     */
    protected $index_name = '';
    /**
     * Generated from protobuf field <code>repeated int64 segmentsIDs = 4;</code>
     */
    private $segmentsIDs;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\MsgBase $base
     *     @type string $collection_name
     *     @type string $index_name
     *     @type int[]|string[]|\Google\Protobuf\Internal\RepeatedField $segmentsIDs
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Feder::initOnce();
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
     * Generated from protobuf field <code>string collection_name = 2;</code>
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collection_name;
    }

    /**
     * Generated from protobuf field <code>string collection_name = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCollectionName($var)
    {
        GPBUtil::checkString($var, True);
        $this->collection_name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string index_name = 3;</code>
     * @return string
     */
    public function getIndexName()
    {
        return $this->index_name;
    }

    /**
     * Generated from protobuf field <code>string index_name = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setIndexName($var)
    {
        GPBUtil::checkString($var, True);
        $this->index_name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated int64 segmentsIDs = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSegmentsIDs()
    {
        return $this->segmentsIDs;
    }

    /**
     * Generated from protobuf field <code>repeated int64 segmentsIDs = 4;</code>
     * @param int[]|string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSegmentsIDs($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT64);
        $this->segmentsIDs = $arr;

        return $this;
    }

}

