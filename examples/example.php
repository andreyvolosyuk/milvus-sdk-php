<?php

require '../vendor/autoload.php';

use Milvus\Proto\Schema\DataType;
use Volosyuk\MilvusPhp\Client\ConnectionPool;
use Volosyuk\MilvusPhp\Client\ConnectionPool as ConnectionPoolAlias;
use Volosyuk\MilvusPhp\Client\Hit;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;
use Volosyuk\MilvusPhp\ORM\Collection;
use Volosyuk\MilvusPhp\ORM\Schema\CollectionSchema;
use Volosyuk\MilvusPhp\ORM\Schema\FieldSchema;
use Volosyuk\MilvusPhp\Settings;

const USING = "default";
const COLLECTION_NAME = "demo";
const ID_FIELD_NAME = "id_field";
const VECTOR_FIELD_NAME = "float_vector_field";
const DIM = 128;
const INDEX_TYPE = "IVF_FLAT";
const NLIST = 1024;
const METRIC_TYPE = 'L2';
const NPROBE = 16;
const TOPK = 3;


function createCollection(string $collectionName, string $idField, string $vectorField): Collection
{
    $schema = new CollectionSchema([
        new FieldSchema($idField, DataType::Int64, "int64", ['is_primary' => true]),
        new FieldSchema($vectorField, DataType::FloatVector, "float vector", ['dim' => DIM]),
    ], "collection description");
    $collection = new Collection($collectionName, $schema);
    printf("\ncollection created: %s\n", $collectionName);

    return $collection;
}


function createIndex(Collection $collection, string $vectorFieldName): \Volosyuk\MilvusPhp\ORM\Schema\Index
{
    $index_param = [
        "index_type"    => INDEX_TYPE,
        "params"        => [
            "nlist"     => NLIST
        ],
        "metric_type"   => METRIC_TYPE
    ];
    $index = $collection->createIndex($vectorFieldName, '', $index_param);

    list($indexedRows, $totalRows) = $index->getBuildProgress();
    while ($indexedRows < $totalRows) {
        #printf("Indexed: %.2f%%\r", 100 * ($indexedRows / $totalRows));
        sleep(1.5);
        list($indexedRows, $totalRows) = $index->getBuildProgress();
    }

    printf("\nCreated index:\n%s\n", json_encode($index->getParams()));

    return $index;
}

/**
 * @throws \Volosyuk\MilvusPhp\Exceptions\FieldNotFoundException
 * @throws SchemaNotReadyException
 * @throws \Volosyuk\MilvusPhp\Exceptions\MilvusException
 * @throws \Volosyuk\MilvusPhp\Exceptions\ParamException
 * @throws \Volosyuk\MilvusPhp\Exceptions\DataNotMatchException
 * @throws \Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException
 * @throws ReflectionException
 * @throws \Volosyuk\MilvusPhp\Exceptions\GRPCException
 */
function main()
{
    $SETTINGS = new Settings([
        'host' => '127.0.0.1',
        'port' => 19530,
        //'secure' => true,
        //"clientPemPath" => "../docker/config/cert/client.pem",
        //"clientKeyPath" => "../docker/config/cert/client.key",
        //"caPemPath" => "../docker/config/cert/ca.pem",
        //"serverName"    => "localhost"
    ]);
    ConnectionPoolAlias::getInstance()->connect(USING, $SETTINGS);

    # drop collection if the collection exists
    try {
        $collection = new Collection(COLLECTION_NAME);
        $collection->drop();
        printf("\nDrop collection: %s\n", COLLECTION_NAME);
    } catch (SchemaNotReadyException $e) {
    }

    # create collection
    $collection = createCollection(COLLECTION_NAME, ID_FIELD_NAME, VECTOR_FIELD_NAME);

    # show collections
    print("\nlist collections:\n");
    $collections = array_map(function (Collection $collection) {
        return $collection->getName();
    }, Collection::getAll(USING));
    printf("['%s']\n\n", implode('\', \'', $collections));

    # insert 10000 vectors with 128 dimension
    $json_string = file_get_contents('data/example.json');
    $data = json_decode($json_string);
    $collection->insert($data);
    $vectors = $data[1];

    $segmentIDs = $collection->flush();
    $flushed = false;
    while (!$flushed) {
        $flushed = $collection->getFlushState($segmentIDs);
        #printf("Flushed: %s\r", $flushed ? "True" : "False");
        sleep(1.5);
    }

    # get the number of entities
    printf("The number of entity:\n%d\n", $collection->getEntityNum());

    # create index
    createIndex($collection, VECTOR_FIELD_NAME);

    # load data to memory
    $collection->load();
    $loaded = 0;
    while ($loaded < 100) {
        sleep(3.0);
        $loaded = $collection->getLoadgingProgress();
        #printf("%d%%\r", $loaded);
    }

    $searchVectors = array_slice($vectors, 0, 3);
    # search
    $results = $collection->search(
        $searchVectors,
        VECTOR_FIELD_NAME,
        [
            "metric_type" => METRIC_TYPE,
            "params" => ["nprobe" => NPROBE]
        ],
        TOPK,
        sprintf("%s >= 0", ID_FIELD_NAME)
    );
    foreach ($results as $i => $result) {
        printf("\nSearch result for %dth vector: \n", $i);

        /**
         * @var Hit $res
         */
        foreach ($result as $j => $res) {
            printf("Top %d: (distance: %.15f, id: %d)\n", $j, $res->getDistance(), $res->getId());
        }
    }

    # release memory
    $collection->release();

    $collection->getIndex(VECTOR_FIELD_NAME)->drop();
    print("\nDrop index sucessfully");

    # drop collection
    $collection->drop();
    printf("\nDrop collection: %s", COLLECTION_NAME);
}

main();