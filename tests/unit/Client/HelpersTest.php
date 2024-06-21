<?php

use PHPUnit\Framework\TestCase;
use Volosyuk\MilvusPhp\Client\Hit;
use function Volosyuk\MilvusPhp\Client\isLegalPort;
use function Volosyuk\MilvusPhp\Client\isLegalHost;
use function Volosyuk\MilvusPhp\Client\isLegalAddress;

require_once "src/Client/helpers.php";

/**
 * @covers Volosyuk\MilvusPhp\Client\isLegalPort
 * @covers Volosyuk\MilvusPhp\Client\isLegalHost
 * @covers Volosyuk\MilvusPhp\Client\isLegalAddress
 */
class HelpersTest extends TestCase
{
    /**
     * @param $port
     * @param bool $expected
     *
     * @dataProvider portDataProvider
     */
    public function testIsLegalPort($port, bool $expected)
    {
        $this->assertEquals(isLegalPort($port), $expected);
    }

    public function portDataProvider(): array
    {
        return [
            [45, true],
            [45.4, false],
            ["45", true],
            ["45a", false],
            ["45.", false],
        ];
    }

    /**
     * @param $host
     * @param bool $expected
     *
     * @dataProvider hostDataProvider
     */
    public function testIsLegalHost($host, bool $expected)
    {
        $this->assertEquals(isLegalHost($host), $expected);
    }

    public function hostDataProvider(): array
    {
        return [
            ["localhost", true],
            ["", false],
            ["localhost:9000", false],
            ["192.168.0.13", true],
            [-1, false],
            ["9000", true],
        ];
    }

    /**
     * @param string $address
     * @param bool $expected
     *
     * @dataProvider addressDataProvider
     */
    public function testIsLegalAddress(string $address, bool $expected)
    {
        $this->assertEquals(isLegalAddress($address), $expected);
    }

    public function addressDataProvider(): array
    {
        return [
            ["localhost:80", true],
            ["", false],
            ["localhost:90:00", false],
            ["localhost:80.5", false],
            ["localhost:123a", false],
        ];
    }
}
