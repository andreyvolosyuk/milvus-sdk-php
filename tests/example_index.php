<?php

require '../vendor/autoload.php';

use Milvus\Proto\Schema\DataType;
use Volosyuk\MilvusPhp\Client\ConnectionPool as ConnectionPoolAlias;
use Volosyuk\MilvusPhp\Client\Hit;
use Volosyuk\MilvusPhp\Exceptions\FieldNotFoundException;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Exceptions\MilvusException;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;
use Volosyuk\MilvusPhp\ORM\Collection;
use Volosyuk\MilvusPhp\ORM\Schema\CollectionSchema;
use Volosyuk\MilvusPhp\ORM\Schema\FieldSchema;
use Volosyuk\MilvusPhp\Settings;


const USING = "default";
const COLLECTION_NAME = "demo";
const ID_FIELD_NAME = "id_field";
const VECTOR_FIELD_NAME = "float_vector_field";
# Scalar
const ATTR1_NAME = "attr1";
const ATTR2_NAME = "attr2";

const DIM = 128;

const INDEX_TYPE = "IVF_FLAT";
const NLIST = 1024;
const METRIC_TYPE = 'L2';
const NPROBE = 1024;
const TOPK = 3;

function createCollection(string $collectionName, string $idField, string $vectorField, string $attr1_name, string $attr2_name): Collection
{
    $schema = new CollectionSchema([
        new FieldSchema($idField, DataType::VarChar, "varchar", ['is_primary' => true, 'max_length' => 10]),
        new FieldSchema($vectorField, DataType::FloatVector, "float vector", ['dim' => DIM]),
        new FieldSchema($attr1_name, DataType::Int64, "attr1"),
        new FieldSchema($attr2_name, DataType::Double, "attr2"),
    ], "collection description");
    $collection = new Collection($collectionName, $schema);
    printf("collection created: %s\n", $collectionName);

    return $collection;
}


/**
 * @throws MilvusException
 * @throws FieldNotFoundException
 * @throws GRPCException
 */
function createIndex(Collection $collection, string $fieldName, string $indexName, array $indexParam): \Volosyuk\MilvusPhp\ORM\Schema\Index
{
    $index = $collection->createIndex($fieldName, $indexName, $indexParam);

    list($indexedRows, $totalRows) = $index->getBuildProgress();
    while ($indexedRows < $totalRows) {
        sleep(0.5);
        list($indexedRows, $totalRows) = $index->getBuildProgress();
    }

    $encoded_params = json_encode($index->getParams());
    printf("\nCreated index:\n%s\n", str_replace(':', ': ', str_replace(',', ', ', str_replace('"', '\'', $encoded_params))));

    return $index;
}

function marshall($subject): string {
    return str_replace(':', ': ', str_replace(',', ', ', str_replace('"', '\'', json_encode($subject))));
}

/**
 * @throws FieldNotFoundException
 * @throws SchemaNotReadyException
 * @throws MilvusException
 * @throws \Volosyuk\MilvusPhp\Exceptions\ParamException
 * @throws \Volosyuk\MilvusPhp\Exceptions\DataNotMatchException
 * @throws \Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException
 * @throws ReflectionException
 * @throws GRPCException
 */
function main()
{
    $SETTINGS = new Settings([
        'host' => '127.0.0.1',
        'port' => 19530
    ]);
    ConnectionPoolAlias::getInstance()->connect(USING, $SETTINGS);

        # drop collection if the collection exists
    try {
        $collection = new Collection(COLLECTION_NAME);
        $collection->drop();
    } catch (SchemaNotReadyException $e) {
    }

    # create collection
    $collection = createCollection(COLLECTION_NAME, ID_FIELD_NAME, VECTOR_FIELD_NAME, ATTR1_NAME, ATTR2_NAME);

    # show collections
    print("\nlist collections:\n");
    $collections = array_map(function (Collection $collection) {
        return $collection->getName();
    }, Collection::getAll(USING));
    printf("['%s']\n\n", implode('\', \'', $collections));

    # insert 10000 vectors with 128 dimension
    $json_string = file_get_contents('data/example_index.json');
    $data = json_decode($json_string);
    $collection->insert($data);
    //print(json_encode(array_slice($data, -3, 3)));
    $vectors = $data[1];

    $segmentIDs = $collection->flush();
    $flushed = false;
    while (!$flushed) {
        $flushed = $collection->getFlushState($segmentIDs);
        #printf("Flushed: %s\r", $flushed ? "True" : "False");
        sleep(0.5);
    }

    # get the number of entities
    printf("The number of entity:\n%d\n", $collection->getEntityNum());

    $vectorIndexParam = [
        "index_type"    => INDEX_TYPE,
        "params"        => [
            "nlist"     => NLIST
        ],
        "metric_type"   => METRIC_TYPE
    ];
    $vectorIndexName = "vector_index";
    $vectorIndex = createIndex($collection, VECTOR_FIELD_NAME, $vectorIndexName, $vectorIndexParam);
    printf("has_index %s: %s", $vectorIndexName, $collection->hasIndex() ? "True" : "False");

    printf("\nindex: %s", marshall($vectorIndex->toArray()));
    printf("\nindex building progress: {'total_rows': %d, 'indexed_rows': %d}\n", ...$vectorIndex->getBuildProgress());


    $scalarIndexParam = [
        "index_type"    => "Trie"
    ];
    $varcharIndexName = "varchar_id_index";
    $scalarIndex = createIndex($collection, ID_FIELD_NAME, $varcharIndexName, $scalarIndexParam);
    printf("has_index %s:  %s\n", $varcharIndexName, $collection->hasIndex('', $varcharIndexName) ? "True" : "False");

    print("all indexes:\n");
    foreach ($collection->getIndexes() as $index) {
        printf("%s\n", marshall($index->toArray()));
    }
    printf("index building progress: {'total_rows': %d, 'indexed_rows': %d}\n", ...$scalarIndex->getBuildProgress());

    $collection->load();

    $loaded = 0;
    while ($loaded < 100) {
        sleep(0.5);
        $loaded = $collection->getLoadgingProgress();
    }

    $searchVectors = array_slice($vectors, 0, 3);
    $results = $collection->search(
        $searchVectors,
        VECTOR_FIELD_NAME,
        [
            "metric_type" => METRIC_TYPE,
            "params" => ["nprobe" => NPROBE]
        ],
        TOPK,
        sprintf('%s >= "0"', ID_FIELD_NAME)
    );
    foreach ($results as $i => $result) {
        printf("\nSearch result for %dth vector: \n", $i);

        /**
         * @var Hit $res
         */
        foreach ($result as $j => $res) {
            printf("Top %d: id: %d, distance: %.15f\n", $j, $res->getId(), $res->getDistance());
        }
    }

    $collection->release();

    $vectorIndex->drop();
    print("\nDrop index sucessfully\n");
    $scalarIndex->drop();
    print("\nDrop index sucessfully\n");


    # drop collection
    $collection->drop();
    printf("\nDrop collection: %s", COLLECTION_NAME);
}

main();
