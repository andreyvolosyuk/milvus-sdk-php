<?php


namespace Volosyuk\MilvusPhp\Utils;

use Milvus\Proto\Common\ConsistencyLevel;
use ValueError;

/**
 * @param int|string $val
 *
 * @return int
 *
 * @throw ValueError
 */
function valueToConsistencyLevel($val): int {
    if (is_int($val) && ConsistencyLevel::name($val)) {
        return $val;
    } elseif (is_string($val) ) {
        return ConsistencyLevel::value($val);
    }

    throw new ValueError("Invalid consistency level " . $val);
}