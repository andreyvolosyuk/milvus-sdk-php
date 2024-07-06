<?php


namespace unit\ORM;


use Grpc\Channel;
use Grpc\UnaryCall;
use Milvus\Proto\Common\ConsistencyLevel;
use Milvus\Proto\Common\ErrorCode;
use Milvus\Proto\Common\Status;
use Milvus\Proto\Milvus\BoolResponse;
use Milvus\Proto\Milvus\DescribeCollectionResponse;
use Milvus\Proto\Milvus\MilvusServiceClient as MilvusServiceClientBase;
use Milvus\Proto\Schema\DataType;
use PHPUnit\Framework\TestCase;
use Volosyuk\MilvusPhp\Client\GRPCHandler;
use Volosyuk\MilvusPhp\Client\MilvusServiceClient;
use Volosyuk\MilvusPhp\Exceptions\SchemaNotReadyException;
use Volosyuk\MilvusPhp\ORM\Collection;
use Volosyuk\MilvusPhp\ORM\Schema\CollectionSchema;
use Volosyuk\MilvusPhp\ORM\Schema\FieldSchema;

/**
 * @covers Volosyuk\MilvusPhp\ORM\Collection
 */
class CollectionTest extends TestCase
{
    const ALIAS = "default";

    protected function setUp()
    {
        $this->cp = $this->getConnectionPoolMock();
    }

    /**
     * @return void
     */
    private function getConnectionPoolMock()
    {
        $this->handler = $this
            ->getMockBuilder(GRPCHandler::class)
            ->disableOriginalConstructor()
            ->setMethods(["getStub", "call", "getTimeout", "getUser", "getAddress", "isConnected"])
            ->getMock();

        $stubMethods = ["HasCollection", "DescribeCollection"];
        $stub = $this
            ->getMockBuilder(MilvusServiceClientBase::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([])
            ->getMock();

        foreach ($stubMethods as $stubMethod) {
            $unaryCall = $this
                ->getMockBuilder(UnaryCall::class)
                ->disableOriginalConstructor()
                ->getMock();

            $stub->method($stubMethod)->willReturn($unaryCall);
        }

        $this
            ->handler
            ->method("getStub")
            ->willReturn($stub);

        $this
            ->handler
            ->method("getUser")
            ->willReturn("user");

        $this
            ->handler
            ->method("getAddress")
            ->willReturn("address");

        $this
            ->handler
            ->method("isConnected")
            ->willReturn(true);

        $this
            ->handler
            ->method("getTimeout")
            ->willReturn(10);
    }

    protected function createCollection(string $name, CollectionSchema $schema = null, string $using = "default", int $shardsNum = 2, $collectionParams = [])
    {
        $collection = $this
            ->getMockBuilder(Collection::class)
            ->setMethods(["getClient"])
            ->disableOriginalConstructor()
            ->getMock();

        $collection->method("getClient")->willReturn(new MilvusServiceClient($this->handler));
        $construct = $this->getObjectMethod($collection, '__construct');
        $construct($name, $schema, $using, $shardsNum, $collectionParams);

        return $collection;
    }

    private function getResponseStatus(int $code)
    {
        return new Status([
            'error_code' => $code
        ]);
    }

    private function getBoolResponse(bool $value, int $responseCode)
    {
        return new BoolResponse([
            "value" => $value,
            "status" => $this->getResponseStatus($responseCode)
        ]);
    }

    private function getDescribeCollectionResponse(CollectionSchema $schema, int $consistency_level, int $responseCode)
    {
        $response = new DescribeCollectionResponse([
            "status" => $this->getResponseStatus($responseCode),
            "schema" => $schema->toGRPC(),
            "consistency_level" => $consistency_level
        ]);

        return $response;
    }

    private function getFields(): array
    {
        return [
            new FieldSchema("id", DataType::Int64, "int64", ['is_primary' => true]),
            new FieldSchema("vector", DataType::FloatVector, "float vector", ['dim' => 128]),
        ];
    }

    private function getSchema()
    {
        return new CollectionSchema(
            $this->getFields(),
            "collection description"
        );
    }

    public function test_consistency_level_mismatch_results_in_exception()
    {
        $schema = $this->getSchema();

        $this
            ->handler
            ->method("call")
            ->willReturnOnConsecutiveCalls(
                $this->getBoolResponse(true, ErrorCode::Success),
                $this->getDescribeCollectionResponse($schema, ConsistencyLevel::Strong, ErrorCode::Success)
            );

        $this->expectException(SchemaNotReadyException::class);
        $this->createCollection("collection", $schema, "default", 2, [
            "consistency_level" => ConsistencyLevel::Bounded
        ]);
    }

    public function test_schema_mismatch_results_in_exception()
    {
        $schema = $this->getSchema();
        $serverSchema = new CollectionSchema(array_merge($this->getFields(), [new FieldSchema("third field", DataType::VarChar, "varchar")]));

        $this
            ->handler
            ->method("call")
            ->willReturnOnConsecutiveCalls(
                $this->getBoolResponse(true, ErrorCode::Success),
                $this->getDescribeCollectionResponse($serverSchema, ConsistencyLevel::Strong, ErrorCode::Success)
            );

        $this->expectException(SchemaNotReadyException::class);
        $this->createCollection("collection", $schema, "default", 2, [
            "consistency_level" => ConsistencyLevel::Strong
        ]);
    }

    public function test_exception_on_collection_create_without_the_schema()
    {
        $this
            ->handler
            ->method("call")
            ->willReturnOnConsecutiveCalls(
                $this->getBoolResponse(false, ErrorCode::Success)
            );

        $this->expectException(SchemaNotReadyException::class);
        $this->createCollection("collection");
    }

    protected function getObjectMethod($object, $methodName)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Can not get method of non object');
        }
        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $reflectionMethod->setAccessible(true);
        return function () use ($object, $reflectionMethod) {
            return $reflectionMethod->invokeArgs($object, func_get_args());
        };
    }
}