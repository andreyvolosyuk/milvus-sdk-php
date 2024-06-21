<?php

namespace Volosyuk\MilvusPhp\Client;


use DateTime;
use DateTimeImmutable;
use Google\Protobuf\Internal\MapField;
use Google\Protobuf\Internal\RepeatedField;
use Grpc\UnaryCall;
use Milvus\Proto\Common\ClientInfo;
use Milvus\Proto\Common\ConsistencyLevel;
use Milvus\Proto\Common\DslType;
use Milvus\Proto\Common\ErrorCode;
use Milvus\Proto\Common\KeyValuePair;
use Milvus\Proto\Common\PlaceholderGroup;
use Milvus\Proto\Common\PlaceholderType;
use Milvus\Proto\Common\PlaceholderValue;
use Milvus\Proto\Common\Status;
use Milvus\Proto\Milvus\BoolResponse;
use Milvus\Proto\Milvus\ConnectRequest;
use Milvus\Proto\Milvus\ConnectResponse;
use Milvus\Proto\Milvus\CreateCollectionRequest;
use Milvus\Proto\Milvus\CreateCredentialRequest;
use Milvus\Proto\Milvus\CreateIndexRequest;
use Milvus\Proto\Milvus\CreateRoleRequest;
use Milvus\Proto\Milvus\DeleteCredentialRequest;
use Milvus\Proto\Milvus\DescribeCollectionRequest;
use Milvus\Proto\Milvus\DescribeCollectionResponse;
use Milvus\Proto\Milvus\DescribeIndexRequest;
use Milvus\Proto\Milvus\DescribeIndexResponse;
use Milvus\Proto\Milvus\DropCollectionRequest;
use Milvus\Proto\Milvus\DropIndexRequest;
use Milvus\Proto\Milvus\DropRoleRequest;
use Milvus\Proto\Milvus\FlushRequest;
use Milvus\Proto\Milvus\FlushResponse;
use Milvus\Proto\Milvus\GetCollectionStatisticsRequest;
use Milvus\Proto\Milvus\GetCollectionStatisticsResponse;
use Milvus\Proto\Milvus\GetFlushStateRequest;
use Milvus\Proto\Milvus\GetFlushStateResponse;
use Milvus\Proto\Milvus\GetIndexBuildProgressRequest;
use Milvus\Proto\Milvus\GetIndexBuildProgressResponse;
use Milvus\Proto\Milvus\GetLoadingProgressRequest;
use Milvus\Proto\Milvus\GetLoadingProgressResponse;
use Milvus\Proto\Milvus\GrantEntity;
use Milvus\Proto\Milvus\GrantorEntity;
use Milvus\Proto\Milvus\HasCollectionRequest;
use Milvus\Proto\Milvus\IndexDescription;
use Milvus\Proto\Milvus\InsertRequest;
use Milvus\Proto\Milvus\ListCredUsersRequest;
use Milvus\Proto\Milvus\ListCredUsersResponse;
use Milvus\Proto\Milvus\LoadCollectionRequest;
use Milvus\Proto\Milvus\MilvusServiceClient as MilvusServiceClientBase;
use Milvus\Proto\Milvus\MutationResult;
use Milvus\Proto\Milvus\ObjectEntity;
use Milvus\Proto\Milvus\OperatePrivilegeRequest;
use Milvus\Proto\Milvus\OperatePrivilegeType;
use Milvus\Proto\Milvus\OperateUserRoleRequest;
use Milvus\Proto\Milvus\OperateUserRoleType;
use Milvus\Proto\Milvus\PrivilegeEntity;
use Milvus\Proto\Milvus\ReleaseCollectionRequest;
use Milvus\Proto\Milvus\RoleEntity;
use Milvus\Proto\Milvus\RoleResult;
use Milvus\Proto\Milvus\SearchRequest;
use Milvus\Proto\Milvus\SearchResults;
use Milvus\Proto\Milvus\SelectGrantRequest;
use Milvus\Proto\Milvus\SelectGrantResponse;
use Milvus\Proto\Milvus\SelectRoleRequest;
use Milvus\Proto\Milvus\SelectRoleResponse;
use Milvus\Proto\Milvus\SelectUserRequest;
use Milvus\Proto\Milvus\SelectUserResponse;
use Milvus\Proto\Milvus\ShowCollectionsRequest;
use Milvus\Proto\Milvus\ShowCollectionsResponse;
use Milvus\Proto\Milvus\ShowType;
use Milvus\Proto\Milvus\UserEntity;
use Milvus\Proto\Milvus\UserResult;
use Milvus\Proto\Schema\CollectionSchema;
use Milvus\Proto\Schema\DataType;
use Milvus\Proto\Schema\FieldSchema;
use Traversable;
use Volosyuk\MilvusPhp\Exceptions\AmbiguousInstancesException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Exceptions\IndexNotExistException;
use Volosyuk\MilvusPhp\Exceptions\MilvusException;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use Volosyuk\MilvusPhp\ORM\Schema\DataEntity;
use Volosyuk\MilvusPhp\ORM\Schema\GrantItem;
use Volosyuk\MilvusPhp\ORM\Schema\Role;
use Volosyuk\MilvusPhp\ORM\Schema\User;
use function Volosyuk\MilvusPhp\ORM\Schema\arrayToKeyValuePairs;
use function Volosyuk\MilvusPhp\ORM\Schema\keyValuePairsSearchByName;
use const Volosyuk\MilvusPhp\ORM\COMMON_TYPE_PARAMS_NORMALIZERS;


class MilvusServiceClient
{
    /**
     * @var int|null
     */
    private $timeout;

    /**
     * @var GRPCHandler|null $grpcHandler
     */
    private $grpcHandler;

    /**
     * @var string $milvusClientClass
     */
    public $milvusClientClass = MilvusServiceClientBase::class;

    /**
     * @var MilvusServiceClientBase|null $grpcHandler
     */
    private $client = null;

    /**
     * @param GRPCHandler $grpcHandler
     * @param int|null $timeout
     *
     * @throws GRPCException|MilvusException
     */
    public function __construct(GRPCHandler $grpcHandler, int $timeout = null)
    {
        $this->timeout = $timeout ?? $grpcHandler->getTimeout();
        $this->grpcHandler = $grpcHandler;
        if (!$grpcHandler->isConnected()) {
            $this->connect();
            $grpcHandler->connected();
        }
    }

    /**
     * @throws GRPCException|MilvusException
     * @throws \Exception
     */
    public function connect()
    {
        $clientInfo = (new ClientInfo())
            ->setSdkType("PHP")
            ->setHost(gethostname())
            ->setLocalTime((new DateTimeImmutable('now'))->format("Y-m-d H:i:s.u"));

        if ($this->grpcHandler->getUser()) {
            $clientInfo->setUser($this->grpcHandler->getUser());
        }

        $connectRequest = (new ConnectRequest())
            ->setClientInfo($clientInfo);

        $func = $this
            ->getClient()
            ->Connect($connectRequest);

        /**
         * @var ConnectResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        $identifier = $response->getIdentifier();
        $this->grpcHandler->setHeader('identifier', $identifier);
        $this->grpcHandler->connected();
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws GRPCException|MilvusException
     */
    public function hasCollection(string $name): bool
    {
        $request = new HasCollectionRequest();
        $request->setCollectionName($name);
        $func = $this
            ->getClient()
            ->HasCollection(
                $request,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var BoolResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return $response->getValue();
    }

    /**
     * @param string $name
     *
     * @throws GRPCException|MilvusException
     */
    public function dropCollection(string $name)
    {
        $request = new DropCollectionRequest();
        $request->setCollectionName($name);
        $func = $this
            ->getClient()
            ->DropCollection($request, $this->getMetaData(), $this->getOptions());
        $this->callAndProcessStatus($func);
    }

    /**
     * @param string $name
     *
     * @throws GRPCException|MilvusException
     */
    public function releaseCollection(string $name)
    {
        $request = new ReleaseCollectionRequest();
        $request->setCollectionName($name);
        $func = $this->getClient()->ReleaseCollection($request, $this->getMetaData(), $this->getOptions());
        $this->callAndProcessStatus($func);
    }

    /**
     * @param string[] $collectionNames
     *
     * @return MapField
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function flushCollections(array $collectionNames): MapField
    {
        foreach ($collectionNames as $collectionName) {
            ParamChecker::isLegalCollectionName($collectionName);
        }

        $flushCollectionsRequest = (new FlushRequest())
            ->setCollectionNames($collectionNames);

        $func = $this
            ->getClient()
            ->Flush($flushCollectionsRequest);

        /**
         * @var $response FlushResponse
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return $response->getCollSegIDs();
    }

    /**
     * @param array $segmentIDs
     *
     * @return bool
     *
     * @throws MilvusException|GRPCException
     */
    public function getFlushState(array $segmentIDs): bool
    {
        $getFlushStateRequest = (new GetFlushStateRequest())
            ->setSegmentIDs($segmentIDs);

        $func = $this
            ->getClient()
            ->GetFlushState($getFlushStateRequest);

        /**
         * @var GetFlushStateResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return $response->getFlushed();
    }

    /**
     * @param string $name
     * @param int|null $replica_number
     *
     * @throws GRPCException
     * @throws MilvusException
     */
    public function loadCollection(string $name, int $replica_number = 1)
    {
        $request = (new LoadCollectionRequest())
            ->setCollectionName($name)
            ->setReplicaNumber($replica_number);

        $func = $this->getClient()->LoadCollection(
            $request,
            $this->getMetaData(),
            $this->getOptions()
        );
        $this->callAndProcessStatus($func);
    }

    /**
     * @param string $name
     *
     * @return int
     *
     * @throws GRPCException|MilvusException
     */
    public function getLoadingProgress(string $name): int
    {
        $request = (new GetLoadingProgressRequest())
            ->setCollectionName($name);

        $func = $this->getClient()->GetLoadingProgress(
            $request,
            $this->getMetaData(),
            $this->getOptions()
        );

        /**
         * @var GetLoadingProgressResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return $response->getProgress();
    }

    /**
     * @param string $name
     *
     * @return DescribeCollectionResponse
     *
     * @throws GRPCException
     * @throws MilvusException
     * @throws ParamException
     */
    public function describeCollection(string $name): DescribeCollectionResponse
    {
        ParamChecker::isLegalCollectionName($name);

        $request = new DescribeCollectionRequest();
        $request->setCollectionName($name);
        $func = $this
            ->getClient()
            ->describecollection(
                $request,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var DescribeCollectionResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return $response;
    }

    /**
     * @throws GRPCException|MilvusException
     */
    public function createCollection(string $name, CollectionSchema $schema, int $shardsNum = 2, int $consistencyLevel = ConsistencyLevel::Bounded)
    {
        $request = new CreateCollectionRequest();
        $request->setSchema(
            $schema
                ->setName($name)
                ->serializeToString()
        );
        $request->setCollectionName($name);
        $request->setShardsNum($shardsNum);
        $request->setConsistencyLevel($consistencyLevel);

        $func = $this->getClient()->createCollection($request, $this->getMetaData(), $this->getOptions());
        $this->callAndProcessStatus($func);
    }

    /**
     * @param int $type
     * @param array $names
     *
     * @return Traversable|[]string
     *
     * @throws GRPCException|ParamException|MilvusException
     */
    public function showCollections(int $type = ShowType::All, array $names = []): Traversable
    {
        $request = new ShowCollectionsRequest();

        if ($names) {
            foreach ($names as $name) {
                ParamChecker::isLegalCollectionName($name);
            }
            $request->setCollectionNames($names);
        }

        ParamChecker::isLegalShowType($type);

        $func = $this->getClient()->showCollections($request, $this->getMetaData(), $this->getOptions());

        /**
         * @var ShowCollectionsResponse $showCollectionsResponse
         */
        $showCollectionsResponse = $this->call($func);
        $this->checkStatus($showCollectionsResponse->getStatus());

        return $showCollectionsResponse
            ->getCollectionNames()
            ->getIterator();
    }

    /**
     * @param string $collectionName
     * @param DataEntity[] $entities
     * @param string|null $partitionName
     * @param array $insertParams
     *
     * @return MutationResult
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function insert(string $collectionName, array $entities, string $partitionName = null, array $insertParams = []): MutationResult
    {
        # todo check_invalid_binary_vector
        /**
         * @var CollectionSchema $schema
         */
        $schema = $insertParams['schema'] ?? null;
        if (!$schema) {
            $schema = $this->describeCollection($collectionName)->getSchema();
        }

        /**
         * @var FieldSchema[] $fields
         */
        $fields = $schema->getFields();
        $insertRequest = (new InsertRequest())
            ->setCollectionName($collectionName)
            ->setPartitionName($partitionName ?? "_default");

        $primaryKeyIndex = null;
        $autoIDIndex = null;
        $location = []; # todo check on this whether used or not
        foreach ($fields as $fieldKey => $field) {
            if ($field->getIsPrimaryKey()) {
                $primaryKeyIndex = $fieldKey;
            }

            if ($field->getAutoID()) {
                $autoIDIndex = $fieldKey;
                continue;
            }

            $match = False;
            foreach ($entities as $entityKey => $entity) {
                if ($entity->getField()->getName() == $field->getName()) {
                    if ($entity->getField()->getDataType() !== $field->getDataType()) {
                        throw new ParamException(sprintf(
                            ExceptionMessage::FIELD_TYPE_MISMATCH,
                            DataType::name($field->getDataType()),
                            DataType::name($entity->getField()->getDataType()),
                            $field->getName()
                        ));
                    }

                    $fieldDim = 0;
                    $entityDim = 0;
                    if ($entity->getField()->isVector()) {
                        /**
                         * @var KeyValuePair $typeParam
                         */
                        foreach ($field->getTypeParams() as $typeParam) {
                            if ($typeParam->getKey() === "dim") {
                                $fieldDim = COMMON_TYPE_PARAMS_NORMALIZERS[$typeParam->getKey()]($typeParam->getValue());
                                break;
                            }
                        }
                        $entityDim = $entity->getVectorSize();
                    }

                    if ($entity->getField()->getDataType() === DataType::FloatVector && $fieldDim != $entityDim) {
                        throw new ParamException(sprintf(
                            ExceptionMessage::FIELD_DIM_MISMATCH,
                            $fieldDim,
                            $entityDim,
                            $field->getName()
                        ));
                    }

                    if ($entity->getField()->getDataType() === DataType::BinaryVector && $fieldDim !== $entityDim * 8) {
                        throw new ParamException(sprintf(
                            ExceptionMessage::FIELD_DIM_MISMATCH,
                            $fieldDim,
                            $entityDim * 8,
                            $field->getName()
                        ));
                    }

                    $location[$field->getName()] = $entityKey;
                    $match = True;
                    break;
                }
            }

            if (!$match) {
                throw new ParamException(sprintf(
                    ExceptionMessage::FIELD_NOT_FOUND_IN_ENTITIES,
                    $field->getName()
                ));
            }
        }

        if (is_null($primaryKeyIndex)) {
            throw new ParamException(ExceptionMessage::PRIMARY_KEY_NOT_FOUND);
        }

        if (is_null($autoIDIndex) && count($fields) !== count($entities)) {
            throw new ParamException(sprintf(
                ExceptionMessage::COLLECTION_ENTITY_FIELD_NUM_MISMATCH,
                count($fields),
                count($entities)
            ));
        }

        if (!is_null($autoIDIndex) && count($fields) - 1 !== count($entities)) {
            throw new ParamException(sprintf(
                ExceptionMessage::COLLECTION_ENTITY_FIELD_NUM_MISMATCH,
                count($fields),
                count($entities)
            ));
        }

        $numRows = null;
        $fieldsData = [];
        foreach ($entities as $entity) {
            $entityCount = $entity->getValuesCount();

            if (!is_null($numRows) && $numRows !== $entityCount) {
                throw new ParamException(sprintf(
                    ExceptionMessage::ROW_NUM_MISALIGNED,
                    $entity->getField()->getName()
                ));
            }
            $fieldsData[] = $entity
                ->toFieldData()
                ->toRaw();
            $numRows = $entityCount;
        }

        $insertRequest
            ->setFieldsData($fieldsData)
            ->setNumRows($numRows);

        $func = $this->getClient()->Insert(
            $insertRequest,
            $this->getMetaData(),
            $this->getOptions()
        );

        /**
         * @var MutationResult $mutationResult
         */
        $mutationResult = $this->call($func);
        $this->checkStatus($mutationResult->getStatus());

        return $mutationResult;
    }

    /**
     * @param string $collectionName
     *
     * @return array|string[]
     *
     * @throws GRPCException|MilvusException
     */
    public function getCollectionStatistics(string $collectionName): array
    {
        $statisticsRequest = (new GetCollectionStatisticsRequest())
            ->setCollectionName($collectionName);

        $func = $this->getClient()->GetCollectionStatistics(
            $statisticsRequest,
            $this->getMetaData(),
            $this->getMetaData()
        );

        /**
         * @var GetCollectionStatisticsResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        $statisticsArray = [];

        /**
         * @var KeyValuePair $kvPair
         */
        foreach ($response->getStats() as $kvPair) {
            $statisticsArray[$kvPair->getKey()] = $kvPair->getValue();
        }

        return $statisticsArray;
    }

    /**
     * @throws GRPCException|MilvusException
     */
    public function createIndex(string $collectionName, string $fieldName, string $indexName = '', array $indexParams = [])
    {
        /**
         * @var $fields FieldSchema[]
         */
        $fields = $this->describeCollection($collectionName)
            ->getSchema()
            ->getFields();

        $hasField = false;
        foreach ($fields as $field) {
            if ($field->getName() === $fieldName) {
                $hasField = true;
            }
        }
        if (!$hasField) {
            throw new ParamException(sprintf('Field %s is not existent in the schema', $fieldName));
        }

        $request = (new CreateIndexRequest())
            ->setCollectionName($collectionName)
            ->setFieldName($fieldName)
            ->setIndexName($indexName)
            ->setExtraParams(arrayToKeyValuePairs($indexParams));

        $func = $this->getClient()->CreateIndex($request,
            $this->getMetaData(),
            $this->getOptions()
        );

        $this->callAndProcessStatus($func);
    }

    /**
     * @return IndexDescription[]
     *
     * @throws GRPCException|MilvusException|IndexNotExistException
     */
    public function describeIndexes(string $collectionName, string $fieldName = '', string $indexName = ''): array
    {
        $request = (new DescribeIndexRequest())
            ->setCollectionName($collectionName)
            ->setFieldName($fieldName)
            ->setIndexName($indexName);

        $func = $this->getClient()->DescribeIndex(
            $request,
            $this->getMetaData(),
            $this->getOptions()
        );

        /**
         * @var DescribeIndexResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        $indexesIterator = $response
            ->getIndexDescriptions()
            ->getIterator();

        return iterator_to_array($indexesIterator);
    }

    /**
     * @param string $collectionName
     * @param string $fieldName
     * @param string $indexName
     *
     * @return IndexDescription
     *
     * @throws IndexNotExistException|GRPCException|MilvusException
     */
    public function getIndex(string $collectionName, string $fieldName, string $indexName = ''): IndexDescription
    {
        return $this->describeIndexes(
            $collectionName,
            $fieldName,
            $indexName
        )[0];
    }

    /**
     * @param string $collectionName
     * @param string $fieldName
     * @param string $indexName
     *
     * @return bool
     *
     * @throws GRPCException|MilvusException
     */
    public function hasIndex(string $collectionName, string $fieldName = '', string $indexName = ''): bool
    {
        /**
         * @var IndexDescription[] $indexes
         */
        try {
            $this->getIndex($collectionName, $fieldName, $indexName);
            return true;
        } catch (IndexNotExistException $e) {
            return false;
        }
    }

    /**
     * @param string $collectionName
     * @param string $fieldName
     * @param string $indexName
     * @return array|int[]
     *
     * @throws GRPCException
     * @throws MilvusException
     */
    public function getIndexBuildProgress(string $collectionName, string $fieldName, string $indexName): array
    {
        $request = (new GetIndexBuildProgressRequest())
            ->setCollectionName($collectionName)
            ->setFieldName($fieldName)
            ->setIndexName($indexName);

        $func = $this
            ->getClient()
            ->GetIndexBuildProgress(
                $request,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var GetIndexBuildProgressResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return [$response->getIndexedRows(), $response->getTotalRows()];
    }

    /**
     * @param string $collectionName
     * @param string $fieldName
     * @param string $indexName
     *
     * @return void
     *
     * @throws GRPCException|MilvusException
     */
    public function dropIndex(string $collectionName, string $fieldName, string $indexName = '')
    {
        $dropIndexRequest = (new DropIndexRequest())
            ->setCollectionName($collectionName)
            ->setFieldName($fieldName)
            ->setIndexName($indexName);

        $func = $this
            ->getClient()
            ->DropIndex(
                $dropIndexRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);
    }

    /**
     * @throws ParamException|GRPCException|MilvusException
     */
    public function search(
        string           $collectionName,
        array            $data,
        string           $annsFieldName,
        array            $param,
        int              $limit,
        string           $expr = null,
        array            $partitionNames = [],
        array            $outputFields = [],
        int              $roundDecimal = -1,
        CollectionSchema $schema = null,
        int              $consistencyLevel = null,
        int              $guaranteeTimestamp = 0,
        int              $travelTimestamp = 0,
        array            $properties = []
    ): ChunkedQueryResult
    {
        ParamChecker::checkArray([
            "limit" => $limit,
            "roundDecimal" => $roundDecimal,
            "annsField" => $annsFieldName,
            "searchData" => $data,
            "partitionNameArray" => $partitionNames,
            "outputFields" => $outputFields,
            "travelTimestamp" => $travelTimestamp,
            "guaranteeTimestamp" => $guaranteeTimestamp
        ]);

        if (is_null($schema) || is_null($consistencyLevel)) {
            $descCollectionResponse = $this->describeCollection($collectionName);
            $schema = $schema ?? $descCollectionResponse->getSchema();
            $consistencyLevel = $consistencyLevel ?? $descCollectionResponse->getConsistencyLevel();
        }

        ## todo handle construct guarantee ts

        #### Preparing requests
        /**
         * @var FieldSchema[]|RepeatedField $fields
         */
        $fields = $schema->getFields();

        $requests = [];
        if (count($data) > 0) {
            if (false) { # todo check binary vector
                $isBinary = true;
                $placeholderType = PlaceholderType::BinaryVector;
            } else {
                $isBinary = false;
                $placeholderType = PlaceholderType::FloatVector;
            }

            /**
             * @var FieldSchema $annsField
             */
            $annsField = null;
            foreach ($fields as $field) {
                if ($annsFieldName === $field->getName()) {
                    $annsField = $field;
                    break;
                }
            }

            if (!$annsField) {
                throw new ParamException(sprintf("Field %s does not exist in schema", $annsFieldName));
            }

            $dimension = intval(keyValuePairsSearchByName("dim", $annsField->getTypeParams(), 0));
            $ignoreGrowing = $param["ignore_growing"] ?? $properties["ignore_growing"] ?? false;
            $params = $param["params"] ?? [];
            if (!is_array($params)) {
                throw new ParamException(sprintf("Search params must be a dict, got %s", gettype($params)));
            }
            $searchParams = [
                "anns_field" => $annsFieldName,
                "topk" => $limit,
                "metric_type" => $param["metric_type"] ?? "L2",
                "params" => $params,
                "round_decimal" => $roundDecimal,
                "offset" => $param["offset"] ?? 0,
                "ignore_growing" => intval($ignoreGrowing)
            ];

            $nq = count($data);

            $placeholderValues = [];
            for ($i = 0; $i < $nq; $i++) {
                $datum = $data[$i];

                if ($isBinary) {
                    if (count($datum) * 8 !== $dimension) {
                        throw new ParamException(sprintf("The dimension of query entities[%s] is different from schema [%s]", $data[$i] * 8, $dimension));
                    }

                    $placeholderValues[] = $datum; ## todo handle binary
                } else {
                    if (count($data[$i]) !== $dimension) {
                        throw new ParamException(sprintf("The dimension of query entities[%s] is different from schema [%s]", $data[$i], $dimension));
                    }

                    $placeholderValues[] = pack(str_repeat("f", count($datum)), ...$datum);
                }
            }
            $placeholder = (new PlaceholderValue())
                ->setTag("$0")
                ->setType($placeholderType)
                ->setValues($placeholderValues);

            $placeholderGroup = (new PlaceholderGroup())
                ->setPlaceholders([$placeholder]);

            $searchRequest = (new SearchRequest())
                ->setCollectionName($collectionName)
                ->setPartitionNames($partitionNames)
                ->setOutputFields($outputFields)
                ->setGuaranteeTimestamp($guaranteeTimestamp)
                ->setTravelTimestamp($travelTimestamp)
                ->setNq($nq)
                ->setPlaceholderGroup($placeholderGroup->serializeToString())
                ->setDslType(DslType::BoolExprV1)
                ->setSearchParams(arrayToKeyValuePairs($searchParams));

            if ($expr) {
                $searchRequest->setDsl($expr);
            }
            $requests[] = $searchRequest;
        }

        $raws = [];
        foreach ($requests as $request) {
            $func = $this
                ->getClient()
                ->Search(
                    $request,
                    $this->getMetaData(),
                    $this->getOptions()
                );

            /**
             * @var SearchResults $response
             */
            $response = $this->call($func);
            $this->checkStatus($response->getStatus());
            $raws[] = $response;
        }

        return new ChunkedQueryResult(
            $raws,
            $schema->getAutoID(),
            $roundDecimal
        );
    }

    /**
     * @param string $userName
     * @param string $password
     *
     * @return User
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function creatUser(string $userName, string $password): User
    {
        ParamChecker::checkArray([
            "userName" => $userName
        ]);

        $createUserRequest = (new CreateCredentialRequest())
            ->setUsername($userName)
            ->setPassword(base64_encode($password));

        $func = $this
            ->getClient()
            ->CreateCredential(
                $createUserRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);

        $user = new User($userName);
        $user->setClient($this);
        return $user;
    }

    /**
     * @param string $userName
     *
     * @return void
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function dropUser(string $userName)
    {
        ParamChecker::checkArray([
            "userName" => $userName
        ]);

        $dropUserRequest = (new DeleteCredentialRequest())
            ->setUsername($userName);

        $func = $this
            ->getClient()
            ->DeleteCredential(
                $dropUserRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);
    }

    /**
     * @return string[]
     *
     * @throws GRPCException
     * @throws MilvusException
     */
    public function listUsernames(): array
    {
        $listUsernamesRequest = new ListCredUsersRequest();

        $func = $this
            ->getClient()
            ->ListCredUsers($listUsernamesRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var ListCredUsersResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        return iterator_to_array($response
            ->getUsernames()
            ->getIterator());
    }

    /**
     * @param string $userName
     *
     * @return User|null
     *
     * @throws AmbiguousInstancesException|GRPCException|MilvusException|ParamException
     */
    public function describeUser(string $userName)
    {
        ParamChecker::checkArray([
            "userName" => $userName
        ]);

        $userEntity = (new UserEntity())
            ->setName($userName);

        $selectUserRequest = (new SelectUserRequest())
            ->setUser($userEntity)
            ->setIncludeRoleInfo(true);

        $func = $this
            ->getClient()
            ->SelectUser($selectUserRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var SelectUserResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        /**
         * @var UserResult[] $users
         */
        $users = iterator_to_array($response->getResults()->getIterator());
        if (count($users) > 1) {
            throw new AmbiguousInstancesException(sprintf(
                ExceptionMessage::AMBIGUOUS_INSTANCES_FOUND,
                "user",
                $userName)
            );
        } elseif (!$users) {
            return null;
        }

        $userResult = $users[0];
        $user = new User($userName, array_map(function (RoleEntity $roleEntity) {
            $role = new Role($roleEntity->getName());
            $role->setClient($this);
            return $role;
        }, iterator_to_array($userResult->getRoles()->getIterator())));
        $user->setClient($this);
        return $user;
    }

    /**
     * @param string $roleName
     *
     * @return Role
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function createRole(string $roleName): Role
    {
        ParamChecker::checkArray([
            "roleName" => $roleName
        ]);

        $roleEntity = (new RoleEntity())
            ->setName($roleName);

        $createRoleRequest = (new CreateRoleRequest())
            ->setEntity($roleEntity);


        $func = $this
            ->getClient()
            ->CreateRole(
                $createRoleRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);

        $role = new Role($roleName);
        $role->setClient($this);
        return $role;
    }

    /**
     * @param string $roleName
     *
     * @return Role
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function describeRole(string $roleName): Role
    {
        ParamChecker::checkArray([
            "roleName" => $roleName
        ]);

        $role = (new RoleEntity())
            ->setName($roleName);

        $entity = (new GrantEntity())
            ->setRole($role); # todo set db_name

        $selectGrantRequest = (new SelectGrantRequest())
            ->setEntity($entity);

        $func = $this
            ->getClient()
            ->SelectGrant($selectGrantRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var SelectGrantResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        $grantEntityIterator = $response
            ->getEntities()
            ->getIterator();

        $role = new Role(
            $roleName,
            array_map(function (GrantEntity $grantEntity) {
                return GrantItem::fromGrantEntity($grantEntity);
            }, iterator_to_array($grantEntityIterator))
        );
        $role->setClient($this);
        return $role;
    }

    /**
     * @param string $roleName
     *
     * @throws ParamException|GRPCException|MilvusException
     */
    public function dropRole(string $roleName)
    {
        ParamChecker::checkArray([
            "roleName" => $roleName
        ]);

        $dropRoleRequest = (new DropRoleRequest())
            ->setRoleName($roleName);

        $func = $this
            ->getClient()
            ->DropRole(
                $dropRoleRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);
    }

    /**
     * @return string[]
     *
     * @throws GRPCException|MilvusException
     */
    public function listRoles(): array
    {
        $selectRoleRequest = (new SelectRoleRequest())
            ->setIncludeUserInfo(false);

        $func = $this
            ->getClient()
            ->SelectRole($selectRoleRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        /**
         * @var SelectRoleResponse $response
         */
        $response = $this->call($func);
        $this->checkStatus($response->getStatus());

        $roleResultIterator = $response
            ->getResults()
            ->getIterator();

        return array_map(function (RoleResult $roleResult) {
            return $roleResult
                ->getRole()
                ->getName();
        }, iterator_to_array($roleResultIterator));
    }

    /**
     * @param string $roleName
     * @param string $object
     * @param string $objectName
     * @param string $privilege
     * @param string $dbName
     * @param int $operatePrivilegeType
     *
     * @return void
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    private function operatePrivilege(string $roleName, string $object, string $objectName,
                                      string $privilege, string $dbName,
                                      int $operatePrivilegeType)
    {
        ParamChecker::checkArray([
            "roleName" => $roleName,
            "object" => $object,
            "objectName" => $objectName,
            "privilege" => $privilege
        ]);

        $roleEntity = (new RoleEntity())
            ->setName($roleName);

        $objectEntity = (new ObjectEntity())
            ->setName($object);

        $privilegeEntity = (new PrivilegeEntity())
            ->setName($privilege);

        $grantorEntity = (new GrantorEntity())
            ->setPrivilege($privilegeEntity);

        $entity = (new GrantEntity())
            ->setRole($roleEntity)
            ->setObject($objectEntity)
            ->setObjectName($objectName)
            ->setDbName($dbName)
            ->setGrantor($grantorEntity);

        $privilegeRequest = (new OperatePrivilegeRequest())
            ->setEntity($entity)
            ->setType($operatePrivilegeType);

        $func = $this
            ->getClient()
            ->OperatePrivilege(
                $privilegeRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);
    }

    /**
     * @param string $roleName
     * @param string $object
     * @param string $objectName
     * @param string $privilege
     * @param string $dbName
     *
     * @return void
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function grantPrivilege(string $roleName, string $object, string $objectName,
                                   string $privilege, string $dbName = "")
    {
        $this->operatePrivilege(
            $roleName,
            $object,
            $objectName,
            $privilege,
            $dbName,
            OperatePrivilegeType::Grant
        );
    }

    /**
     * @param string $roleName
     * @param string $object
     * @param string $objectName
     * @param string $privilege
     * @param string $dbName
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function revokePrivilege(string $roleName, string $object, string $objectName,
                                    string $privilege, string $dbName = "")
    {
        $this->operatePrivilege(
            $roleName,
            $object,
            $objectName,
            $privilege,
            $dbName,
            OperatePrivilegeType::Revoke
        );
    }

    /**
     * @param string $userName
     * @param string $roleName
     * @param int $type
     *
     * @return void
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    private function operateRole(string $userName, string $roleName, int $type)
    {
        ParamChecker::checkArray([
            "userName" => $userName,
            "roleName" => $roleName
        ]);

        $operateRoleRequest = (new OperateUserRoleRequest())
            ->setUsername($userName)
            ->setRoleName($roleName)
            ->setType($type);

        $func = $this
            ->getClient()
            ->OperateUserRole(
                $operateRoleRequest,
                $this->getMetaData(),
                $this->getOptions()
            );

        $this->callAndProcessStatus($func);
    }

    /**
     * @param string $userName
     * @param string $roleName
     *
     * @return void
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function grantRole(string $userName, string $roleName)
    {
        $this->operateRole(
            $userName,
            $roleName,
            OperateUserRoleType::AddUserToRole
        );
    }

    /**
     * @param string $userName
     * @param string $roleName
     *
     * @return void
     *
     * @throws GRPCException|MilvusException|ParamException
     */
    public function revokeRole(string $userName, string $roleName)
    {
        $this->operateRole(
            $userName,
            $roleName,
            OperateUserRoleType::RemoveUserFromRole
        );
    }

    /**
     * @param UnaryCall $call
     *
     * @return mixed
     *
     * @throws GRPCException
     */
    private function call(UnaryCall $call)
    {
        return $this->grpcHandler->call($call);
    }

    /**
     * @throws GRPCException|MilvusException
     */
    private function callAndProcessStatus(UnaryCall $call)
    {
        /**
         * @var Status $response
         */
        $response = $this->call($call);

        $this->checkStatus($response);
    }

    /**
     * @throws MilvusException
     */
    private function checkStatus(Status $status)
    {
        if ($status->getErrorCode() !== ErrorCode::Success) {
            # check whether respective exception mapping exists
            $excCls = sprintf(
                "Volosyuk\MilvusPhp\Exceptions\%sException",
                ErrorCode::name($status->getErrorCode())
            );

            if (class_exists($excCls)) {
                throw new $excCls(
                    $status->getReason(),
                    $status->getErrorCode()
                );
            }

            throw new MilvusException(
                $status->getReason(),
                $status->getErrorCode()
            ); # todo raise appropriate exception
        }
    }

    private function getOptions(): array
    {
        $options = [];

        if ($this->timeout !== null) {
            $options['timeout'] = $this->timeout;
        }

        return $options;
    }

    private function getMetaData(): array
    {
        return [];
    }

    private function getClient(): MilvusServiceClientBase
    {
        if ($this->client === null) {
            $this->client = $this->grpcHandler->getStub($this->milvusClientClass);
        }

        return $this->client;
    }
}