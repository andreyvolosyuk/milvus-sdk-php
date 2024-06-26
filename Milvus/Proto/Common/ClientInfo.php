<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: common.proto

namespace Milvus\Proto\Common;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>milvus.proto.common.ClientInfo</code>
 */
class ClientInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * sdk_type can be `python`, `golang`, `nodejs` and etc. It's not proper to make `sdk_type` an
     * enumerate type, since we cannot always update the enum value everytime when newly sdk is supported.
     *
     * Generated from protobuf field <code>string sdk_type = 1;</code>
     */
    protected $sdk_type = '';
    /**
     * Generated from protobuf field <code>string sdk_version = 2;</code>
     */
    protected $sdk_version = '';
    /**
     * Generated from protobuf field <code>string local_time = 3;</code>
     */
    protected $local_time = '';
    /**
     * Generated from protobuf field <code>string user = 4;</code>
     */
    protected $user = '';
    /**
     * Generated from protobuf field <code>string host = 5;</code>
     */
    protected $host = '';
    /**
     * reserved for newly-added feature if necessary.
     *
     * Generated from protobuf field <code>map<string, string> reserved = 6;</code>
     */
    private $reserved;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $sdk_type
     *           sdk_type can be `python`, `golang`, `nodejs` and etc. It's not proper to make `sdk_type` an
     *           enumerate type, since we cannot always update the enum value everytime when newly sdk is supported.
     *     @type string $sdk_version
     *     @type string $local_time
     *     @type string $user
     *     @type string $host
     *     @type array|\Google\Protobuf\Internal\MapField $reserved
     *           reserved for newly-added feature if necessary.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Common::initOnce();
        parent::__construct($data);
    }

    /**
     * sdk_type can be `python`, `golang`, `nodejs` and etc. It's not proper to make `sdk_type` an
     * enumerate type, since we cannot always update the enum value everytime when newly sdk is supported.
     *
     * Generated from protobuf field <code>string sdk_type = 1;</code>
     * @return string
     */
    public function getSdkType()
    {
        return $this->sdk_type;
    }

    /**
     * sdk_type can be `python`, `golang`, `nodejs` and etc. It's not proper to make `sdk_type` an
     * enumerate type, since we cannot always update the enum value everytime when newly sdk is supported.
     *
     * Generated from protobuf field <code>string sdk_type = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setSdkType($var)
    {
        GPBUtil::checkString($var, True);
        $this->sdk_type = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string sdk_version = 2;</code>
     * @return string
     */
    public function getSdkVersion()
    {
        return $this->sdk_version;
    }

    /**
     * Generated from protobuf field <code>string sdk_version = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setSdkVersion($var)
    {
        GPBUtil::checkString($var, True);
        $this->sdk_version = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string local_time = 3;</code>
     * @return string
     */
    public function getLocalTime()
    {
        return $this->local_time;
    }

    /**
     * Generated from protobuf field <code>string local_time = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setLocalTime($var)
    {
        GPBUtil::checkString($var, True);
        $this->local_time = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string user = 4;</code>
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>string user = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkString($var, True);
        $this->user = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string host = 5;</code>
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Generated from protobuf field <code>string host = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setHost($var)
    {
        GPBUtil::checkString($var, True);
        $this->host = $var;

        return $this;
    }

    /**
     * reserved for newly-added feature if necessary.
     *
     * Generated from protobuf field <code>map<string, string> reserved = 6;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getReserved()
    {
        return $this->reserved;
    }

    /**
     * reserved for newly-added feature if necessary.
     *
     * Generated from protobuf field <code>map<string, string> reserved = 6;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setReserved($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::STRING);
        $this->reserved = $arr;

        return $this;
    }

}

