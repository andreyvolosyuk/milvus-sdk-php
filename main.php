<?php

require 'vendor/autoload.php';

use Grpc\ChannelCredentials;
use Milvus\Proto\Milvus\DescribeCollectionRequest;
use Milvus\Proto\Milvus\HasCollectionRequest;
use Milvus\Proto\Milvus\MilvusServiceClient;
use Milvus\Proto\Schema\DataType;
use Volosyuk\MilvusPhp\Client\ChunkedQueryResult;
use Volosyuk\MilvusPhp\Client\ConnectionPool;
use Volosyuk\MilvusPhp\Client\ConnectionPool as ConnectionPoolAlias;

#use Volosyuk\MilvusPhp\Client\MilvusServiceClient;
use Volosyuk\MilvusPhp\ORM\Collection;
use Volosyuk\MilvusPhp\ORM\Schema\CollectionSchema;
use Volosyuk\MilvusPhp\ORM\Schema\FieldSchema;
use Volosyuk\MilvusPhp\Settings;


function rand_float($st_num=0,$end_num=1,$mul=1000000)
{
    return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
}

function generate_vector(int $length): array
{
    $v = [];
    for ($i = 0; $i < $length; $i++) {
        $v[] = rand_float(0, 100);
    }

    return $v;
}

function generate_vectors(int $length, int $v_length): array
{
    $vectors = [];
    for ($i = 0; $i < $length; $i++) {
        $vectors[] = generate_vector($v_length);
    }

    return $vectors;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generate_strings(int $length): array
{
    $strings = [];
    for ($i = 0; $i < $length; $i++) {
        $strings[] = generateRandomString(20);
    }

    return $strings;
}

function generate_ids(int $length): array
{
    return range(0, $length - 1);
}

function main()
{
    #$service = new MilvusServiceClient('localhost:19530', [
    #    'credentials' => Grpc\ChannelCredentials::createInsecure()
    #]);
    #$r = new HasCollectionRequest();
    #$r->setCollectionName('demo');
    #var_dump($service->HasCollection($r)->wait());
    $settings = new Settings([
        'host' => '127.0.0.1',
        'port' => 19530,
        'secure' => true,

    ]);

    $grpcHandler = ConnectionPoolAlias::getInstance()->connect('default', $settings);
    #$service = new \Volosyuk\MilvusPhp\Client\MilvusServiceClient($grpcHandler);
    #$service->dropCollection('demo2');

    $collection = new Collection('demo', new CollectionSchema([
        new FieldSchema('id_field', DataType::Int64, "int64", ["is_primary" => true, 'auto_id' => false]),
        new FieldSchema('float_vector_field', DataType::FloatVector, "float vector", ["dim" => 2]),
        new FieldSchema('extra_string', DataType::VarChar, "string", ["max_length" => 20]),
    ], "collection description"));

    var_dump($collection->exists());
    $vectors = generate_vectors(100, 2);
    $res = $collection->insert([
        generate_ids(100),
       $vectors,
        generate_strings(100),
    ]);
    #var_dump($res->getStatus());
    $collection->flush();
    #var_dump(iterator_to_array($res->getIDs()->getIntId()->getData()->getIterator()));
    #var_dump($collection->getEntityNum());
    $collection->release();
    $collection->createIndex('float_vector_field', 'vf_index', [
        "index_type" => "IVF_FLAT",
        "metric_type" => "L2",
        "params" => [
            "nlist" => 1024
        ]
    ]);
    $collection->release();
    #$collection->createIndex('id_field', 'id_index');
    #$collection->dropIndex('float_vector_field', 'vf_index');
    #var_dump($collection->hasIndex('float_vector_field', 'vf_indexn'));

    #var_dump($collection->getIndex('float_vector_field', 'vf_index')->getParams());

    $collection->load();
    $searchVectors = array_slice($vectors, 0, 3);
    /**
     * @var ChunkedQueryResult $res
     */
    $res = $collection->search(
        $searchVectors,
        "float_vector_field",
        [
            "metric_type" => "L2",
            "params" => [
                "nprobe" => 16
            ]
        ],
        3,
        "id_field >= 0",
        [],
        ["extra_string"],
        2
    );

    foreach ($res as $key => $value) {
        var_dump($key);

        foreach ($value as $item) {
            print(sprintf("id: %d, distance: %f\n", $item->getId(), $item->getDistance()));
        }
    }
}

main();