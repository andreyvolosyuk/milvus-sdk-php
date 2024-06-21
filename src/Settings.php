<?php

namespace Volosyuk\MilvusPhp;

use Exception;

/**
 * @property string $uri
 * @property string $address
 * @property string $host
 * @property int    $port
 * @property int    $timeout
 * @property string $user
 * @property string $password
 * @property string $token
 * @property string $dbName
 * @property bool   $secure
 * @property string $clientPemPath
 * @property string $clientKeyPath
 * @property string $caPemPath
 * @property string $serverPemPath
 * @property string $serverName
 * @property array $options
 */
class Settings {
    const DEFAULT_TIMEOUT = 10e6;
    const DEFAULT_HOST = "localhost";
    const DEFAULT_PORT = 19530;
    const DEFAULT_PORT_SSL = 443;

    const DEFAULTS = [
        "uri" => "",
        "address" => "",
        "host" => "localhost",
        "port" => self::DEFAULT_PORT,
        "secure" => false,
        "timeout" => self::DEFAULT_TIMEOUT,
        "clientPemPath" => "",
        "clientKeyPath" => "",
        "caPemPath" => "",
        "serverPemPath" => "",
        "serverName" => "localhost",
        "user" => "",
        "password" => "",
        "token" => "",
        "dbName" => "",
    ];

    /**
     * @var array
     */
    private $settings;

    public function __construct(array $settings)
    {
        $validSettings = array_intersect_key($settings, static::DEFAULTS);

        $this->settings = array_merge(static::DEFAULTS, $validSettings);
    }

    /**
     * @throws Exception
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->settings)) {
            if (array_key_exists($name, static::DEFAULTS)) {
                return static::DEFAULTS[$name];
            }

            throw new Exception('No key ' . $name); // todo raise proper exception
        }

        return $this->settings[$name];
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return void
     *
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        if (!array_key_exists($name, $this->settings)) {
            throw new Exception('No key ' . $name); // todo raise proper exception
        }

        $this->settings[$name] = $value;
    }

    /**
     * @return string
     */
    public function getGRPCAddress(): string
    {
        return sprintf("%s:%d", $this->host, $this->port);
    }
}