<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;


use Volosyuk\MilvusPhp\Exceptions\ParamException;
use const Volosyuk\MilvusPhp\ORM\BASIC_INDEX_METRICS;
use const Volosyuk\MilvusPhp\ORM\BASIC_INDEX_TYPES;
use const Volosyuk\MilvusPhp\ORM\BIN_FLAT;
use const Volosyuk\MilvusPhp\ORM\BIN_FLAT_INDEX_METRICS;
use const Volosyuk\MilvusPhp\ORM\BIN_IVF_FLAT;
use const Volosyuk\MilvusPhp\ORM\BIN_IVF_FLAT_INDEX_METRICS;
use const Volosyuk\MilvusPhp\ORM\BINARY_INDEX_TYPES;
use const Volosyuk\MilvusPhp\ORM\VALID_INDEX_PARAMS_KEYS;

class VectorIndex extends Index
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $metricType;

    /**
     * @param string $fieldName
     * @param string $collectionName
     * @param string $name
     * @param array $params
     *
     * @throws ParamException
     */
    public function __construct(string $fieldName, string $collectionName, string $name = "", array $params = [])
    {
        $validParamKeys = ["index_type", "params", "metric_type"];
        if (count($validParamKeys) !== count(array_intersect($validParamKeys, array_keys($params)))) {
            throw new ParamException(sprintf(
                "Index params must consist of elements with next keys: %s",
                implode(", ", $validParamKeys)
            ));
        }

        $illegalParamKeys = array_diff(array_keys($params), $validParamKeys);
        if ($illegalParamKeys) {
            throw new ParamException(sprintf(
                "Illegal index params passed: %s",
                implode(",", $illegalParamKeys)
            ));
        }

        if (!is_array($params['params'])) {
            throw new ParamException("Index params['params'] must be an array");
        }

        $type = $params["index_type"];
        $validIndexTypes = array_merge(BASIC_INDEX_TYPES, BINARY_INDEX_TYPES);
        if (!in_array($type, $validIndexTypes, true)) {
            throw new ParamException(sprintf(
                "Invalid index type %s. Valid options: %s",
                $type,
                implode(",", $validIndexTypes)
            ));
        }
        $this->type = $type;

        $indexParams = $params["params"];
        $illegalParamKeys = array_diff(array_keys($indexParams), VALID_INDEX_PARAMS_KEYS);
        if ($illegalParamKeys) {
            throw new ParamException(sprintf(
                "Illegal index params['params'] passed: %s",
                implode(", ", $illegalParamKeys)
            ));
        }
        foreach ($params["params"] as $paramName => $param) {
            if (!is_integer($param)) {
                throw new ParamException(sprintf(
                    "Invalid params' param %s type. Integer is the only valid data type",
                    $paramName
                ));
            }
        }

        $this->metricType = $params["metric_type"];
        if (!$this->isValidMetric()) {
            throw new ParamException(sprintf(
                "%s metric is illegal for %s index type",
                $this->metricType,
                $this->type
            ));
        }

        parent::__construct($fieldName, $collectionName, $name, $params);
    }

    /**
     * @return bool
     */
    public function isValidMetric(): bool
    {
        if ($this->type === BIN_FLAT) {
            return in_array($this->metricType, BIN_FLAT_INDEX_METRICS, true);
        } elseif ($this->type === BIN_IVF_FLAT) {
            return in_array($this->metricType, BIN_IVF_FLAT_INDEX_METRICS, true);
        }

        return in_array($this->metricType, BASIC_INDEX_METRICS, true);
    }
}
