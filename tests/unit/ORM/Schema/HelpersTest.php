<?php

namespace unit\ORM\Schema;


use Milvus\Proto\Common\ConsistencyLevel;
use Milvus\Proto\Schema\DataType;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;
use Volosyuk\MilvusPhp\Exceptions\DataTypeNotSupportException;
use function Volosyuk\MilvusPhp\ORM\Schema\inferConsistencyLevel;
use function Volosyuk\MilvusPhp\ORM\Schema\inferDataType;

require_once "src/Client/helpers.php";

/**
 * @covers Volosyuk\MilvusPhp\ORM\Schema\inferDataType
 * @covers Volosyuk\MilvusPhp\ORM\Schema\inferConsistencyLevel
 */
class HelpersTest extends TestCase
{
    /**
     * @param $value
     * @param string $expectedDType
     *
     * @dataProvider dataTypeDataProvider
     * @throws DataTypeNotSupportException
     */
    public function test_data_type_inference($value, string $expectedDType)
    {
        $this->assertEquals(inferDataType($value), $expectedDType);
    }

    /**
     * @param $value
     *
     * @throws DataTypeNotSupportException
     *
     * @dataProvider unsupportedDataTypes
     */
    public function test_unsupported_data_types_cause_data_type_not_supported_exception($value)
    {
        $this->expectException(DataTypeNotSupportException::class);
        inferDataType($value);
    }

    public function test_consistency_level_inference()
    {
        $this->assertEquals(ConsistencyLevel::Session, inferConsistencyLevel(ConsistencyLevel::Session));
        #$this->assertEquals(ConsistencyLevel::Session, inferConsistencyLevel("Strong"));
        $this->expectException(UnexpectedValueException::class);
        inferConsistencyLevel("NoConsitency");
        inferConsistencyLevel(90);
    }

    public function dataTypeDataProvider(): array
    {
        return [
            [[1], DataType::FloatVector],
            [[1.0, 2.0], DataType::FloatVector],
            [1, DataType::Int64],
            [3.3, DataType::Double],
            [false, DataType::Bool],
            [true, DataType::Bool],
            ["abc", DataType::VarChar],
        ];
    }

    public function unsupportedDataTypes(): array
    {
        return [
            [["efg"]],
            [["2"]],
            [["2.0"]],
            [[true]],
        ];
    }
}