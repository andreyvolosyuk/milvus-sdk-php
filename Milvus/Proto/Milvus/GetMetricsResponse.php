<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: milvus.proto

namespace Milvus\Proto\Milvus;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.milvus.GetMetricsResponse</code>
 */
class GetMetricsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.milvus.proto.common.Status status = 1;</code>
     */
    protected $status = null;
    /**
     * response is of jsonic format
     *
     * Generated from protobuf field <code>string response = 2;</code>
     */
    protected $response = '';
    /**
     * metrics from which component
     *
     * Generated from protobuf field <code>string component_name = 3;</code>
     */
    protected $component_name = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Common\Status $status
     *     @type string $response
     *           response is of jsonic format
     *     @type string $component_name
     *           metrics from which component
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
     * response is of jsonic format
     *
     * Generated from protobuf field <code>string response = 2;</code>
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * response is of jsonic format
     *
     * Generated from protobuf field <code>string response = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setResponse($var)
    {
        GPBUtil::checkString($var, True);
        $this->response = $var;

        return $this;
    }

    /**
     * metrics from which component
     *
     * Generated from protobuf field <code>string component_name = 3;</code>
     * @return string
     */
    public function getComponentName()
    {
        return $this->component_name;
    }

    /**
     * metrics from which component
     *
     * Generated from protobuf field <code>string component_name = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setComponentName($var)
    {
        GPBUtil::checkString($var, True);
        $this->component_name = $var;

        return $this;
    }

}

