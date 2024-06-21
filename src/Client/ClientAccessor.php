<?php


namespace Volosyuk\MilvusPhp\Client;


use Volosyuk\MilvusPhp\ORM\Schema\Exception;

trait ClientAccessor
{
    /**
     * @var MilvusServiceClient
     */
    private $client;

    /**
     * @throws Exception
     */
    private function getClient(): MilvusServiceClient
    {
        if (!$this->client) { //todo raise appropriate excetpion
            throw new Exception('Client is not instantiated');
        }

        return $this->client;
    }

    /**
     * @param MilvusServiceClient $client
     */
    public function setClient(MilvusServiceClient $client)
    {
        $this->client = $client;
    }
}