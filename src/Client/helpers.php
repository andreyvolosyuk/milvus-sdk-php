<?php


namespace Volosyuk\MilvusPhp\Client;

/**
 * @param string $host
 *
 * @return bool
 */
function isLegalHost($host): bool
{
    return (
        is_string($host) &&
        strlen($host) > 0 &&
        strpos($host, ":") === false &&
        filter_var($host, FILTER_VALIDATE_DOMAIN)
    );
}

/**
 * @param int|string $port
 *
 * @return bool
 */
function isLegalPort($port): bool
{
    if (is_int($port)) {
        return true;
    }

    if (is_string($port) && filter_var($port, FILTER_VALIDATE_INT)) {
        return true;
    }

    return false;
}

/**
 * @param string $address
 *
 * @return array
 */
function parseAddress(string $address): array
{
    return explode(":", $address);
}

/**
 * @param string $address
 *
 * @return bool
 */
function isLegalAddress(string $address): bool
{
    $addressSplit = parseAddress($address);
    if (count($addressSplit) != 2) {
        return false;
    }
    list($host, $port) = $addressSplit;

    return isLegalHost($host) && isLegalPort($port);
}