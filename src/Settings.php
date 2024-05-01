<?php

namespace Volosyuk\MilvusPhp;

/**
 * @property string $host
 * @property int    $port
 * @property int    $timeout
 * @property string $user
 * @property string $password
 * @property bool   $secure
 * @property string $clientPemPath
 * @property string $clientKeyPath
 * @property string $caPemPath
 * @property string $serverPemPath
 * @property string $serverName
 */
class Settings {
    const GRPC_TIMEOUT = 10e6;

    const DEFAULTS = [
        "secure" => False,
        "timeout" => self::GRPC_TIMEOUT,
        "clientPemPath" => "",
        "clientKeyPath" => "",
        "caPemPath" => "",
        "serverPemPath" => "",
        "serverName" => "localhost",
    ];

    private $HTTP_PORT = 19121;
    private $HTTP_ADDRESS = "127.0.0.1:19121";
    private $HTTP_URI = "http://{HTTP_ADDRESS}";

    private $CALC_DIST_METRIC = "L2";
    /**
     * @var array
     */
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->settings)) {
            if (array_key_exists($name, static::DEFAULTS)) {
                return static::DEFAULTS[$name];
            }

            throw new \Exception('No key ' . $name);
        }

        return $this->settings[$name];
    }

    /**
     * @return string
     */
    public function getGRPCAddress(): string
    {
        return sprintf("%s:%d", $this->host, $this->port);
    }

    /**
     * @return string
     */
    public function getGRPCUri(): string
    {
        return "tcp://" . $this->getGRPCAddress();
    }

    public function getGRPCTimeout(): int
    {
        try {
            return $this->timeout;
        } catch (\Exception $e) {
            return static::GRPC_TIMEOUT;
        }
    }
}