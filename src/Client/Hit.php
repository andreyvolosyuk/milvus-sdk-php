<?php


namespace Volosyuk\MilvusPhp\Client;


class Hit
{
    /**
     * @var string|int
     */
    private $id;

    /**
     * @var array
     */
    private $rowData;

    /**
     * @var float
     */
    private $score;

    /**
     * @var float
     */
    private $distance;

    public function __construct($entityId, array $entityRowData, float $entityScore)
    {
        $this->id = $entityId;
        $this->rowData = $entityRowData;
        $this->score = $entityScore;
        $this->distance = $entityScore;
    }

    /**
     * @return string|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getDistance(): float
    {
        return $this->distance;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }
}