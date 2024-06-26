<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: schema.proto

namespace Milvus\Proto\Schema;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * clustering distribution info of a certain data unit, it can be segment, partition, etc.  
 *
 * Generated from protobuf message <code>milvus.proto.schema.ClusteringInfo</code>
 */
class ClusteringInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.VectorClusteringInfo vector_clustering_infos = 1;</code>
     */
    private $vector_clustering_infos;
    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.ScalarClusteringInfo scalar_clustering_infos = 2;</code>
     */
    private $scalar_clustering_infos;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Milvus\Proto\Schema\VectorClusteringInfo[]|\Google\Protobuf\Internal\RepeatedField $vector_clustering_infos
     *     @type \Milvus\Proto\Schema\ScalarClusteringInfo[]|\Google\Protobuf\Internal\RepeatedField $scalar_clustering_infos
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Schema::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.VectorClusteringInfo vector_clustering_infos = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getVectorClusteringInfos()
    {
        return $this->vector_clustering_infos;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.VectorClusteringInfo vector_clustering_infos = 1;</code>
     * @param \Milvus\Proto\Schema\VectorClusteringInfo[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setVectorClusteringInfos($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Schema\VectorClusteringInfo::class);
        $this->vector_clustering_infos = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.ScalarClusteringInfo scalar_clustering_infos = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getScalarClusteringInfos()
    {
        return $this->scalar_clustering_infos;
    }

    /**
     * Generated from protobuf field <code>repeated .milvus.proto.schema.ScalarClusteringInfo scalar_clustering_infos = 2;</code>
     * @param \Milvus\Proto\Schema\ScalarClusteringInfo[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setScalarClusteringInfos($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Milvus\Proto\Schema\ScalarClusteringInfo::class);
        $this->scalar_clustering_infos = $arr;

        return $this;
    }

}

