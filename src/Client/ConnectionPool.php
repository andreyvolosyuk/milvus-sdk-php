<?php


namespace Volosyuk\MilvusPhp\Client;


use Exception;
use Volosyuk\MilvusPhp\Settings;

class ConnectionPool
{
    /**
     * @var Settings[]
     */
    private $settings = [];

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

    /**
     * @throws Exception
     */
    public function getConnection(string $alias): GRPCHandler
    {
        if (static::$self === null) { # todo handle gracefully
            throw new Exception('Not instantiated');
        }

        if (!array_key_exists($alias, $this->connectionPool)) {
            throw new Exception('Connection was not established');
        }

        return $this->connectionPool[$alias];
    }

    public function connect($alias, Settings $settings): GRPCHandler
    {
        if (!array_key_exists($alias, $this->connectionPool)) {
            #if (!array_key_exists($alias, $this->settings)) {
            #    throw new Exception("No respective configuration");
            #}

            $this->connectionPool[$alias] = GRPCHandler::instantiate($settings);
        }

        return $this->connectionPool[$alias];
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
