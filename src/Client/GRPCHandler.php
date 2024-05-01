<?php

namespace Volosyuk\MilvusPhp\Client;

use Grpc\Channel;
use Grpc\ChannelCredentials;
use Grpc\Internal\InterceptorChannel;
use Grpc\UnaryCall;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Settings;
use const Grpc\STATUS_OK;


class GRPCHandler
{
    /**
     * @var null|Channel
     */
    private $channel;

    /**
     * @var int
     */
    private $timeout;

    private function __construct(Channel $channel, $timeout)
    {
        $this->channel = $channel;
        $this->timeout = $timeout;
    }

    /**
     * @return mixed
     *
     * @throws GRPCException
     */
    public function call(UnaryCall $call)
    {
        list($response, $status) = $call->wait();

        if ($status->code === STATUS_OK) {
            return $response;
        }

        throw new GRPCException($status->details, $status->code);
    }

    /**
     * @return Channel
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function close()
    {
        if ($this->channel) {
            $this->channel->close();
        }
    }

    /**
     * @param Settings $settings
     *
     * @return GRPCHandler
     */
    public static function instantiate(Settings $settings): self
    {
        $channel = new Channel($settings->getGrpcAddress(), [ # todo support security
            'credentials' => static::getCredentials($settings),
            'grpc.ssl_target_name_override' => $settings->serverName
        ]);


        return new self($channel, $settings->getGRPCTimeout());
    }

    /**
     * @param Settings $settings
     * @return ChannelCredentials
     */
    private static function getCredentials(Settings $settings)
    {
        if (!$settings->secure) {
            return ChannelCredentials::createInsecure();
        }

        if ($settings->clientPemPath && $settings->clientKeyPath && $settings->caPemPath && $settings->serverName) {
            $certificateChain = file_get_contents($settings->clientPemPath);
            $privateKey = file_get_contents($settings->clientKeyPath);
            $rootCertificates = file_get_contents($settings->caPemPath);

            return ChannelCredentials::createSsl($rootCertificates, $privateKey, $certificateChain);

        } else if ($settings->serverPemPath && $settings->serverName) {
            $rootCertificates = file_get_contents($settings->serverPemPath);

            return ChannelCredentials::createSsl($rootCertificates);
        }

        return ChannelCredentials::createSsl();
    }

    public function getTarget(): string
    {
        return $this->channel->getTarget();
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
