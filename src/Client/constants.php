<?php

namespace Volosyuk\MilvusPhp\Client;

use Milvus\Proto\Common\ConsistencyLevel;

const LOGICAL_BITS = 18;
const LOGICAL_BITS_MASK = (1 << LOGICAL_BITS) - 1;
const EVENTUALLY_TS = 1;
const BOUNDED_TS = 2;
const DEFAULT_CONSISTENCY_LEVEL = ConsistencyLevel::Bounded;