<?php


namespace Volosyuk\MilvusPhp\Client;


use Exception;
use Volosyuk\MilvusPhp\Exceptions\ConnectionConfigException;
use Volosyuk\MilvusPhp\Exceptions\ExceptionMessage;
use Volosyuk\MilvusPhp\Settings;

class ConnectionPool
{
    /**
     * @var GRPCHandler[]
     */
    private $connectionPool = [];

    /**
     * @var ConnectionPool
     */
    private static $self;

    private function __construct() {}

    private function __clone() {}

    public function __destruct()
    {
        foreach ($this->connectionPool as $connection) {
            $connection->disconnect();
        }
    }

    public function __debugInfo()
    {
        // TODO: Implement __debugInfo() method.
    }

    /**
     * @param string $alias
     *
     * @return GRPCHandler
     *
     * @throws ConnectionConfigException
     */
    public function getConnection(string $alias): GRPCHandler
    {
        if (!$this->isInstantiated()) { # todo handle gracefully
            throw new ConnectionConfigException('ConnectionPool is not instantiated');
        }

        if (!$this->hasConnection($alias)) {
            throw new ConnectionConfigException(sprintf('Connection with the alias %s was not established', $alias));
        }

        return $this->connectionPool[$alias];
    }

    /**
     * @param string $host
     * @param int|string $port
     *
     * @return void
     *
     * @throws ConnectionConfigException
     */
    private function verifyHostPort($host, $port)
    {
        if (!isLegalHost($host)) {
            throw new ConnectionConfigException(sprintf(ExceptionMessage::ILLEGAL_HOST, gettype($host)));
        }

        if (!isLegalPort($port)) {
            throw new ConnectionConfigException(sprintf(ExceptionMessage::ILLEGAL_PORT, gettype($port)));
        }

        $portInt = intval($port);
        if ($portInt < 0 || $portInt >= 65535) {
            throw new ConnectionConfigException(sprintf(ExceptionMessage::ILLEGAL_PORT_RANGE, $port));
        }
    }

    /**
     * @param Settings $settings
     *
     * @return void
     *
     * @throws ConnectionConfigException
     */
    private function parseAddress(Settings $settings)
    {
        if (!$settings->address) {
            throw new ConnectionConfigException("Can not parse empty address.");
        }

        if (!isLegalAddress($settings->address)) {
            throw new ConnectionConfigException(sprintf(
                "The specified address %s does not comply with the format 'localhost:19530'",
                $settings->address
            ));
        }

        list($settings->host, $settings->port) = parseAddress($settings->address);
    }

    /**
     * @param Settings $settings
     *
     * @return void
     *
     * @throws ConnectionConfigException
     */
    private function parseAddressFromUri(Settings $settings)
    {
        $illegalUriMessage = sprintf(
            "Illegal uri: [%s], expected form 'http[s]://[user:password@]example.com[:12345]'",
            $settings->uri
        );
        $exc = new ConnectionConfigException($illegalUriMessage);

        $parsedUri = parse_url($settings->uri);
        if (!$parsedUri) {
            throw $exc;
        }

        // host must be present in url
        if (!array_key_exists("host", $parsedUri) || !$parsedUri["host"]) {
            throw $exc;
        }

        $host = $parsedUri["host"];
        $defaultPort = Settings::DEFAULT_PORT;
        if (array_key_exists('scheme', $parsedUri) && $parsedUri['scheme'] == "https") {
            $defaultPort = Settings::DEFAULT_PORT_SSL;
            $settings->secure = true;
        }
        $port = $parsedUri["port"] ?? $defaultPort;

        $this->verifyHostPort($host, $port);

        $settings->host = $host;
        $settings->port = $port;
        if (!isLegalAddress($settings->getGRPCAddress())) {
            throw $exc;
        }
    }

    /**
     * @param Settings $settings
     *
     * @return void
     *
     * @throws ConnectionConfigException
     */
    private function getFullAddress(Settings $settings)
    {
        if ($settings->address) {
            $this->parseAddress($settings);
            return;
        }

        if ($settings->uri) {
            if (substr($settings->uri, 0, 5) == "unix:") {
                return;
            }

            $this->parseAddressFromUri($settings);
            return;
        }

        $settings->host = $settings->host ?: Settings::DEFAULT_HOST;
        $settings->port = $settings->port ?: Settings::DEFAULT_PORT;
        $this->verifyHostPort($settings->host, $settings->port);

        if (!isLegalAddress($settings->getGRPCAddress())) {
            throw new ConnectionConfigException(sprintf(
                ExceptionMessage::ILLEGAL_HOST_OR_PORT,
                $settings->host,
                $settings->port
            ));
        }
    }

    /**
     * @param string $alias
     * @param Settings $settings
     *
     * @return GRPCHandler
     *
     * @throws Exception
     */
    public function connect(string $alias, Settings $settings): GRPCHandler
    {
        $connectionSettings = clone $settings;

        if (!$this->hasConnection($alias)) {
            $parsedUri = parse_url($connectionSettings->uri);
            $path = $parsedUri['path'] ?? "";
            if ($path && !in_array($parsedUri['scheme'] ?? "", ["unix", "http", "https", "tcp"], true)) {
                # todo manage lite server
            }

            $this->getFullAddress($settings);

            if ($this->hasConnection($alias) && $this->getConnection($alias)->getAddress() !== $settings->getGRPCAddress()) {
                throw new ConnectionConfigException(sprintf(
                    ExceptionMessage::CONNECTION_CONFIGURATION_DISCREPANCY,
                    $alias
                ));
            }

            $connectionSettings->user = $parsedUri["user"] ?? $connectionSettings->user;
            $connectionSettings->password = $parsedUri["pass"] ?? $settings->password;
            $pathGroups = explode("/", $parsedUri["path"] ?? "");
            $connectionSettings->dbName = count($pathGroups) > 1 ? $pathGroups[1] : $settings->dbName;
            if ($parsedUri["scheme"] ?? "" === "https") {
                $connectionSettings->secure = true;
            }

            $this->connectionPool[$alias] = $this->getHandler($settings);
        }

        return $this->connectionPool[$alias];
    }

    /**
     * @param Settings $settings
     *
     * @return GRPCHandler
     *
     * @throws Exception
     */
    protected function getHandler(Settings $settings): GRPCHandler
    {
        return new GRPCHandler($settings);
    }

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function hasConnection(string $alias): bool
    {
        return array_key_exists($alias, $this->connectionPool);
    }

    /**
     * @param string $alias
     *
     * @return void
     *
     * @throws ConnectionConfigException
     */
    public function disconnect(string $alias)
    {
        $this->getConnection($alias)->disconnect();
        unset($this->connectionPool[$alias]);
    }

    public function isInstantiated(): bool
    {
        return self::$self !== null;
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$self === null) {
            self::$self = new static();
        }

        return self::$self;
    }
}
