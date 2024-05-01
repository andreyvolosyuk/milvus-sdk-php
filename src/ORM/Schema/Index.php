<?php


namespace Volosyuk\MilvusPhp\ORM\Schema;

use Exception;
use Volosyuk\MilvusPhp\Client\MilvusServiceClient;
use Volosyuk\MilvusPhp\Client\ParamChecker;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Exceptions\MilvusException;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use const Volosyuk\MilvusPhp\ORM\VECTOR_DATA_TYPES;

abstract class Index
{
    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var string
     */
    private $collectionName;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var MilvusServiceClient
     */
    private $client;

    /**
     * @param string $fieldName
     * @param string $collectionName
     * @param string $name
     * @param array $params
     */
    public function __construct(string $fieldName, string $collectionName, string $name = "", array $params = [])
    {
        $this->fieldName = $fieldName;
        $this->collectionName = $collectionName;
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array
     *
     * @throws GRPCException
     * @throws MilvusException
     *
     * @throws Exception
     */
    public function getBuildProgress(): array
    {

        return $this
            ->getClient()
            ->getIndexBuildProgress(
                $this->collectionName,
                $this->fieldName,
                $this->name
            );
    }

    /**
     * @throws GRPCException
     * @throws MilvusException
     */
    public function drop()
    {
        $this
            ->getClient()
            ->dropIndex(
                $this->collectionName,
                $this->fieldName,
                $this->name
            );
    }

    private function getClient(): MilvusServiceClient
    {
        if (!$this->client) {
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

    /**
     * @param int $type
     * @param string $collectionName
     * @param string $fieldName
     * @param string $name
     * @param array $params
     *
     * @return static
     *
     * @throws ParamException
     */
    public static function fromType(int $type, string $collectionName, string $fieldName, string $name = "", array $params = []): self
    {
        ParamChecker::isLegalDataType($type);

        if (in_array($type, VECTOR_DATA_TYPES, true)) {
            return new VectorIndex($fieldName, $collectionName, $name, $params);
        }

        return new ScalarIndex($fieldName, $collectionName, $name, $params);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "collection"    => $this->collectionName,
            "field"         => $this->fieldName,
            "index_name"    => $this->name,
            "index_param"  => $this->params,
        ];
    }
}