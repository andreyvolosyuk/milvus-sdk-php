<?php


namespace unit\Client;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Volosyuk\MilvusPhp\Client\ConnectionPool;
use Volosyuk\MilvusPhp\Exceptions\ConnectionConfigException;
use Volosyuk\MilvusPhp\Settings;

/**
 * @covers Volosyuk\MilvusPhp\Client\ConnectionPool
 */
class ConnectionPoolTest extends TestCase
{
    const ALIAS = "default";

    protected function setUp()
    {
        $this->cp = $this->getConnectionPoolMock();
    }

    /**
     * @return MockObject|ConnectionPool
     */
    private function getConnectionPoolMock(): MockObject
    {
        $cp = $this->getMockBuilder(ConnectionPool::class)
            ->disableOriginalConstructor()
            ->setMethods(["isInstantiated"])
            ->getMock();

        $cp->method("isInstantiated")->willReturn(true);

        return $cp;
    }

    public function test_connect_with_default_config()
    {
        $settings = new Settings([]);

        
        $this->assertFalse($this->cp->hasConnection(static::ALIAS));
        $this->cp->connect(static::ALIAS, $settings);
        $this->assertTrue($this->cp->hasConnection(static::ALIAS));
        $this->cp->disconnect(static::ALIAS);
        $this->assertFalse($this->cp->hasConnection(static::ALIAS));
    }

    public function test_connect_with_no_host_or_port()
    {
        $settings = new Settings([]);

        
        $this->assertFalse($this->cp->hasConnection(self::ALIAS));
        $this->cp->connect(self::ALIAS, $settings);
        $this->assertTrue($this->cp->hasConnection(self::ALIAS));
        $this->assertEquals(
            "localhost:19530",
            $this->cp->getConnection(self::ALIAS)->getAddress()
        );
        $this->cp->disconnect(self::ALIAS);
    }

    /**
     * @param string $uri
     * @param string $expected
     *
     * @dataProvider uriDataProvider
     *
     * @throws \Exception
     */
    public function test_connect_with_uri(string $uri, string $expected)
    {
        $settings = new Settings([
            "uri" => $uri
        ]);

        
        $this->cp->connect(self::ALIAS, $settings);
        $this->assertEquals(
            $expected,
            $this->cp->getConnection(self::ALIAS)->getAddress()
        );
    }

    /**
     * @param string $uri
     * 
     * @dataProvider invalidUriDataProvider
     */
    public function test_connect_with_invalid_uri_raises_connection_exception($uri)
    {
        $testConnectionSettings = new Settings([
            'uri' => $uri
        ]);
        $this->expectException(ConnectionConfigException::class);
        $this->cp->connect(self::ALIAS, $testConnectionSettings);
    }

    public function test_connection_pool_supports_multiple_connections()
    {
        

        $testConnectionSettings = new Settings([
            "uri" => "127.0.0.1:19350"
        ]);
        $this->cp->connect("test", $testConnectionSettings);
        $this->assertEquals("127.0.0.1:19350", $this->cp->getConnection("test")->getAddress());

        $defaultConnectionSettings = new Settings([
            "uri" => "default:19350"
        ]);
        $this->cp->connect(self::ALIAS, $defaultConnectionSettings);
        $this->assertEquals("default:19350", $this->cp->getConnection(self::ALIAS)->getAddress());
    }

    /**
     * @param $connectionAttributes
     *
     * @dataProvider invalidHostsDataProvider
     */
    public function test_connect_to_invalid_host_raises_connection_exception($connectionAttributes)
    {
        

        $testConnectionSettings = new Settings($connectionAttributes);
        $this->expectException(ConnectionConfigException::class);
        $this->cp->connect(self::ALIAS, $testConnectionSettings);
    }

    /**
     * @param $connectionAttributes
     *
     * @dataProvider invalidPortsDataProvider
     */
    public function test_connect_to_invalid_port_raises_connection_exception($connectionAttributes)
    {
        

        $testConnectionSettings = new Settings($connectionAttributes);
        $this->expectException(ConnectionConfigException::class);
        $this->cp->connect(self::ALIAS, $testConnectionSettings);
    }

    /**
     * @param string $address
     *
     * @dataProvider addressDataProvider
     */
    public function test_connect_using_address(string $address)
    {
        

        $testConnectionSettings = new Settings([
            'address' => $address
        ]);

        $this->cp->connect(self::ALIAS, $testConnectionSettings);
        $this->assertEquals(
            $this->cp->getConnection(self::ALIAS)->getAddress(),
            $address
        );
    }

    /**
     * @param string $address
     *
     * @dataProvider invalidAddressDataProvider
     */
    public function test_connect_using_invalid_address_raises_connection_exception(string $address)
    {
        

        $testConnectionSettings = new Settings([
            'address' => $address
        ]);

        $this->expectException(ConnectionConfigException::class);
        $this->cp->connect(self::ALIAS, $testConnectionSettings);
    }

    public function addressDataProvider(): array
    {
        return [
            ["127.0.0.1:19530"],
            ["example.com:19530"]
        ];
    }

    public function invalidAddressDataProvider(): array
    {
        return [
            ["127.0.0.1"],
            ["19530"]
        ];
    }

    public function invalidHostsDataProvider(): array
    {
        return [
            [["host" => true, "port" => 19530]],
            [["host" => 1, "port" => 19530]],
            [["host" => 1.0, "port" => 19530, "random" => "random"]],
        ];
    }

    public function invalidPortsDataProvider(): array
    {
        return [
            [["host" => "localhost", "port" => new stdClass()]],
            [["host" => "localhost", "port" => 1.0]],
            [["host" => "localhost", "port" => [3]]],
            [["host" => "localhost", "port" => 68000, "random" => "random"]],
        ];
    }

    public function uriDataProvider(): array
    {
        return [
            ["https://127.0.0.1:19530", "127.0.0.1:19530"],
            ["tcp://127.0.0.1:19530", "127.0.0.1:19530"],
            ["http://127.0.0.1:19530", "127.0.0.1:19530"],
            ["http://example.com:80", "example.com:80"],
            ["http://example.com:80/database1", "example.com:80"],
            ["https://127.0.0.1:19530/database2", "127.0.0.1:19530"],
            ["https://127.0.0.1/database3", "127.0.0.1:443"],
            ["http://127.0.0.1/database4", "127.0.0.1:19530"]
        ];
    }

    public function invalidUriDataProvider(): array
    {
        return [
            ["https://"],
            [-1],
            [true],
            [1.0]
        ];
    }
}