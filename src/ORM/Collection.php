<?php

namespace Volosyuk\MilvusPhp\ORM;

use Exception;
use Milvus\Proto\Milvus\IndexDescription;
use Milvus\Proto\Milvus\MutationResult;
use Milvus\Proto\Schema\LongArray;
use ReflectionException;
use Volosyuk\MilvusPhp\Client\ChunkedQueryResult;
use Volosyuk\MilvusPhp\Client\MilvusServiceClient;
use Volosyuk\MilvusPhp\Exceptions\DataNotMatchException;
use Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Exceptions\FieldNotFoundException;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Exceptions\IndexNotExistException;
use Volosyuk\MilvusPhp\Exceptions\MilvusException;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;
use Volosyuk\MilvusPhp\Exceptions\ValueError;
use Volosyuk\MilvusPhp\ORM\Schema\CollectionSchema;
use Volosyuk\MilvusPhp\ORM\Schema\Index;
use function Volosyuk\MilvusPhp\ORM\Schema\checkInsertDataSchema;
use function Volosyuk\MilvusPhp\ORM\Schema\keyValuePairsToArray;
use function Volosyuk\MilvusPhp\ORM\Schema\prepareInsertData;
use function Volosyuk\MilvusPhp\ORM\Schema\inferConsistencyLevel;
use const Volosyuk\MilvusPhp\Client\DEFAULT_CONSISTENCY_LEVEL;

class Collection {
    /**
     * @var string
     */
    private $name;

    /**
     * @var CollectionSchema
     */
    private $schema;

    private $using;

    /**
     * @var int
     */
    private $consitencyLevel;

    /**
     * @var MilvusServiceClient
     */
    private $client;

    /**
     * @param string $name
     * @param CollectionSchema|null $schema
     * @param string $using
     * @param int $shardsNum
     * @param array $collectionParams
     *
     * @throws GRPCException
     * @throws MilvusException
     * @throws ParamException
     * @throws SchemaNotReadyException
     * @throws ReflectionException
     */
    public function __construct(string $name, CollectionSchema $schema = null, string $using = "default", int $shardsNum = 2, $collectionParams = [])
    {
        $this->name = $name;

        $this->using = $using; # todo handle connection pool

        if ($this->exists()) {
            $descCollectionResp = $this
                ->getClient()
                ->describeCollection($this->name);

            $consistencyLevel = $descCollectionResp->getConsistencyLevel();
            if (array_key_exists("consistency_level", $collectionParams)) {
                try {
                    $providedConsistency = inferConsistencyLevel($collectionParams["consistency_level"]);
                } catch (UnexpectedValueException $e) {
                    throw new SchemaNotReadyException($e);
                }

                if ($consistencyLevel !== $providedConsistency) {
                    throw new SchemaNotReadyException(ExceptionMessage::CONSISTENCY_LEVEL_INCONSISTENT);
                }
            }
            $serverSchema = CollectionSchema::fromGRPC($descCollectionResp->getSchema());
            if (!$schema) {
                $this->schema = $serverSchema;
            } else {
                if (!$serverSchema->isEqual($schema)) {
                    throw new SchemaNotReadyException(ExceptionMessage::SCHEMA_INCONSISTENT);
                }
                $this->schema = $schema;
            }
        } else {
            if (!$schema) {
                throw new SchemaNotReadyException(sprintf(ExceptionMessage::COLLECTION_NOT_EXIST_NO_SCHEMA, $this->name));
            }
            $consistencyLevel = $collectionParams["consistency_level"] ?? DEFAULT_CONSISTENCY_LEVEL;
            $this->getClient()->createCollection(
                $this->name,
                $schema->toGRPC(),
                $shardsNum,
                $consistencyLevel
            );
            $this->schema = $schema;
            $this->consitencyLevel = $consistencyLevel;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $using
     *
     * @return array|self[]
     *
     * @throws GRPCException
     * @throws MilvusException
     * @throws ParamException
     * @throws ReflectionException
     * @throws SchemaNotReadyException
     * @throws Exception
     */
    public static function getAll(string $using): array
    {
        $collections = [];
        foreach (getClient($using)->showCollections() as $collectionName) {
            $collections[] = new static($collectionName, null, $using);
        }

        return $collections;
    }

    /**
     * @param array $data
     * @param string|null $partitionName
     *
     * @return MutationResult
     *
     * @throws DataNotMatchException|DataTypeNotSupportException|GRPCException|MilvusException|ParamException|Exception
     */
    public function insert(array $data, string $partitionName = null): MutationResult
    {
        checkInsertDataSchema($this->schema, $data);
        $entities = prepareInsertData($this->schema, $data);

        return $this
            ->getClient()
            ->insert($this->name, $entities, $partitionName, [
                "schema" => $this->schema->toGRPC()
            ]);
    }

    /**
     * @throws GRPCException|MilvusException|Exception
     */
    public function release()
    {
        $this
            ->getClient()
            ->releaseCollection($this->name);
    }

    /**
     * @return int[]|array
     *
     * @throws ParamException|GRPCException|MilvusException|Exception
     */
    public function flush(): array
    {
        $segmentIDsMap = $this
            ->getClient()
            ->flushCollections([$this->name]);

        /**
         * @var LongArray $segmentIDs
         */
        $segmentIDs = $segmentIDsMap[$this->name];

        return iterator_to_array($segmentIDs->getData());
    }

    /**
     * @param array|int[] $segmentIDs
     *
     * @return bool
     * @throws GRPCException|MilvusException
     */
    public function getFlushState(array $segmentIDs): bool
    {
        return $this
            ->getClient()
            ->getFlushState($segmentIDs);
    }

    /**
     * @return array
     *
     * @throws GRPCException|MilvusException|Exception
     */
    public function getStatistics(): array
    {
        return $this
            ->getClient()
            ->getCollectionStatistics($this->name);
    }

    /**
     * @return int
     *
     * @throws GRPCException|MilvusException
     */
    public function getEntityNum(): int
    {
        return intval($this->getStatistics()["row_count"] ?? 0);
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function exists(): bool
    {
        return $this
            ->getClient()
            ->hasCollection($this->name);
    }

    /**
     * @throws GRPCException|MilvusException|Exception
     */
    public function drop()
    {
        $this
            ->getClient()
            ->dropCollection($this->name);
    }

    /**
     * @throws GRPCException|MilvusException
     * @throws Exception
     */
    public function load()
    {
        $this
            ->getClient()
            ->loadCollection($this->name);
    }

    /**
     * @return int|string
     *
     * @throws GRPCException|MilvusException|Exception
     */
    public function getLoadgingProgress()
    {
        return $this
            ->getClient()
            ->getLoadingProgress($this->name);
    }

    /**
     * @param string $fieldName
     * @param string $indexName
     * @param array $indexParams
     *
     * @return Index
     *
     * @throws FieldNotFoundException|GRPCException|MilvusException|Exception
     */
    public function createIndex(string $fieldName, string $indexName = '', array $indexParams = []): Index
    {
        $field = $this
            ->schema
            ->getFieldByName($fieldName);

        $this
            ->getClient()
            ->createIndex(
                $this->name,
                $field->getName(),
                $indexName,
                $indexParams
            );

        $index = Index::fromType(
            $field->getDataType(),
            $this->name,
            $field->getName(),
            $indexName,
            $indexParams
        );
        $index->setClient($this->getClient());
        return $index;
    }

    /**
     * @param string $fieldName
     * @param string $indexName
     *
     * @return bool
     *
     * @throws FieldNotFoundException|GRPCException|MilvusException
     */
    public function hasIndex(string $fieldName = '', string $indexName = ''): bool
    {
        if ($fieldName) {
            $fieldName = $this
                ->schema
                ->getFieldByName($fieldName)
                ->getName();
        }

        return $this
            ->getClient()
            ->hasIndex(
                $this->name,
                $fieldName,
                $indexName
            );
    }

    /**
     * @param string $fieldName
     * @param string $indexName
     *
     * @return Index
     *
     * @throws FieldNotFoundException|IndexNotExistException|GRPCException|MilvusException|ParamException
     */
    public function getIndex(string $fieldName, string $indexName = ''): Index
    {
        $field = $this
            ->schema
            ->getFieldByName($fieldName);

        $indexDescription = $this
            ->getClient()
            ->getIndex(
                $this->name,
                $field->getName(),
                $indexName
            );

        return $this->indexDescriptionToIndex($indexDescription);
    }

    /**
     * @return array|Index[]
     *
     * @throws FieldNotFoundException|GRPCException|MilvusException|ParamException|Exception
     */
    public function getIndexes(): array
    {
        $indexes = $this
            ->getClient()
            ->describeIndexes($this->name);

        return array_map(function (IndexDescription $indexDescription) {
            return $this->indexDescriptionToIndex($indexDescription);
        }, $indexes);
    }

    /**
     * @param IndexDescription $indexDescription
     *
     * @return Index
     *
     * @throws FieldNotFoundException|ParamException
     */
    private function indexDescriptionToIndex(IndexDescription $indexDescription): Index
    {
        $index = Index::fromType(
            $this->schema->getFieldByName($indexDescription->getFieldName())->getDataType(),
            $this->name,
            $indexDescription->getFieldName(),
            $indexDescription->getIndexName(),
            keyValuePairsToArray($indexDescription->getParams())
        );
        $index->setClient($this->getClient());
        return $index;
    }

    /**
     * @throws ParamException|GRPCException|MilvusException
     */
    public function search(
        array $data,
        string $annsFieldName,
        array $param,
        int $limit,
        string $expr = null,
        array $partitionNames = [],
        array $outputFields = [],
        int $roundDecimal = -1,
        int $guaranteeTimestamp = 0,
        int $travelTimestamp = 0,
        array $properties = []
    ): ChunkedQueryResult
    {
        return $this->getClient()->search(
            $this->name,
            $data,
            $annsFieldName,
            $param,
            $limit,
            $expr,
            $partitionNames,
            $outputFields,
            $roundDecimal,
            $this->schema->toGRPC(),
            $this->consitencyLevel,
            $guaranteeTimestamp,
            $travelTimestamp,
            $properties
        );
    }

    /**
     * @return MilvusServiceClient
     *
     * @throws Exception
     */
    protected function getClient(): MilvusServiceClient
    {
        if ($this->client === null) {
            $this->client = getClient($this->using);
        }

        return $this->client;
    }
}
