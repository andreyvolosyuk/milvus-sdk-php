<?php

namespace Volosyuk\MilvusPhp\ORM;


use Volosyuk\MilvusPhp\Client\ConnectionPool;
use Volosyuk\MilvusPhp\Client\GRPCHandler;
use Volosyuk\MilvusPhp\Client\MilvusServiceClient;


/**
 * @param string $using
 *
 * @return GRPCHandler
 *
 * @throws \Exception
 */
function getConnection(string $using): GRPCHandler
{
    return ConnectionPool::getInstance()->getConnection($using);
}

/**
 * @throws \Exception
 */
function getClient(string $using): MilvusServiceClient
{
    return new MilvusServiceClient(getConnection($using));
}

/**
 * @param string $collectionName
 * @param string $using
 *
 * @return bool
 *
 * @throws \Volosyuk\MilvusPhp\Exceptions\GRPCException
 * @throws \Exception
 */
#function hasCollection(string $collectionName, string $using = 'default'): bool
#{
#    return getClient($using)->hasCollection($collectionName);
#DS}