<?php

namespace Volosyuk\MilvusPhp\ORM\Schema;


use Milvus\Proto\Schema\DataType;

const SCALARS_DATA_TYPE_FIELD_MAPPER = [
    DataType::Bool      => 'Bool',
    DataType::Int8      => 'Int',
    DataType::Int16     => 'Int',
    DataType::Int32     => 'Int',
    DataType::Int64     => 'Long',
    DataType::Float     => 'Float',
    DataType::Double    => 'Double',
    DataType::VarChar   => 'String',
];

const VECTORS_DATA_TYPE_FIELD_MAPPER = [
    DataType::BinaryVector => 'Binary',
    DataType::FloatVector => 'Float'
];
