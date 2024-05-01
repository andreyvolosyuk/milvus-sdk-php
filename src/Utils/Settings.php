<?php

namespace Volosyuk\MilvusPhp\Utils;

class Settings {
    const GRPC_PORT = 19530;
    const GRPC_HOST = "127.0.0.1";

    private $HTTP_PORT = 19121;
    private $HTTP_ADDRESS = "127.0.0.1:19121";
    private $HTTP_URI = "http://{HTTP_ADDRESS}";

    private $CALC_DIST_METRIC = "L2";

    /**
     * @return int
     */
    public function getGRPCPORT(): int
    {
        return static::GRPC_PORT;
    }

    /**
     * @return string
     */
    public function getGRPCADDRESS(): string
    {
        return static::GRPC_HOST . ":" . static::GRPC_PORT;
    }

    /**
     * @return string
     */
    public function getGRPCURI(): string
    {
        return "tcp://" . $this->getGRPCADDRESS();
    }
}