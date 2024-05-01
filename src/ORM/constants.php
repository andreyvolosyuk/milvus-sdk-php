<?php

namespace Volosyuk\MilvusPhp\ORM;


use Milvus\Proto\Schema\DataType;

const COMMON_TYPE_PARAMS = ["dim", "max_length"];
const COMMON_TYPE_PARAMS_NORMALIZERS = [
    "dim" => "intval",
    "max_length" => "intval",
];

const PRIMARY_KEY_TYPES = [DataType::Int64, DataType::VarChar];
const VECTOR_DATA_TYPES = [DataType::FloatVector, DataType::BinaryVector];

const CALC_DIST_IDS = "ids";
const CALC_DIST_FLOAT_VEC = "float_vectors";
const CALC_DIST_BIN_VEC = "bin_vectors";
const CALC_DIST_METRIC = "metric";
const CALC_DIST_L2 = "L2";
const CALC_DIST_IP = "IP";
const CALC_DIST_HAMMING = "HAMMING";
const CALC_DIST_TANIMOTO = "TANIMOTO";
const CALC_DIST_SQRT = "sqrt";
const CALC_DIST_DIM = "dim";


const BASIC_INDEX_TYPES = [
    "FLAT",
    "IVF_FLAT",
    "IVF_SQ8",
    "IVF_PQ",
    "HNSW",
    "ANNOY",
    "RHNSW_FLAT",
    "RHNSW_PQ",
    "RHNSW_SQ",
    "DISKANN",
    "AUTOINDEX"
];

const BIN_FLAT = "BIN_FLAT";
const BIN_IVF_FLAT = "BIN_IVF_FLAT";
const BINARY_INDEX_TYPES = [
    BIN_FLAT,
    BIN_IVF_FLAT
];

const BIN_FLAT_INDEX_METRICS = [
    "JACCARD",
    "TANIMOTO",
    "HAMMING",
    "SUBSTRUCTURE",
    "SUPERSTRUCTURE"
];

const BIN_IVF_FLAT_INDEX_METRICS = [
    "JACCARD",
    "TANIMOTO",
    "HAMMING"
];

const BASIC_INDEX_METRICS = [
    "L2",
    "IP"
];

const VALID_INDEX_PARAMS_KEYS = [
    "nlist",
    "m",
    "nbits",
    "M",
    "efConstruction",
    "PQM",
    "n_trees"
];
