<?php

namespace Volosyuk\MilvusPhp\Client;

use Exception;
use Grpc\BaseStub;
use Grpc\Channel;
use Grpc\ChannelCredentials;
use Grpc\Interceptor;
use Grpc\Internal\InterceptorChannel;
use Grpc\UnaryCall;
use Volosyuk\MilvusPhp\Exceptions\GRPCException;
use Volosyuk\MilvusPhp\Settings;
use const Grpc\STATUS_OK;


class GRPCHandler
{
    # todo go through all the settings in pymilvus/orm/connections

    /**
     * @var null|Channel
     */
    private $channel;

    /**
     * @var bool
     */
    private $connected = False;

    /**
     * @var HeaderAddderInterceptor
     */
    private $headerAdderInterceptor;

    /**
     * @var int
     */
    private $connectivityState;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param Settings|null $settings
     *
     * @throws Exception
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function __destruct()
    {
        $this->disconnect();
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

    public function close()
    {
        if ($this->channel !== null) {
            $this->channel->close();
        }
    }

    /**
     * @param HeaderAddderInterceptor $interceptor
     * @param Settings $settings
     *
     * @return void
     *
     */
    private static function setupAuthInterceptor(HeaderAddderInterceptor $interceptor, Settings $settings)
    {
        if ($settings->token) {
            $interceptor->setParameter("Authorization", base64_encode($settings->token));
        } elseif ($settings->user && $settings->password) {
            $interceptor->setParameter("Authorization", base64_encode(sprintf("%s:%s", $settings->user, $settings->password)));
        }
    }

    /**
     * @return Channel
     *
     * @throws Exception
     */
    public function getChannel(): Channel
    {
        if ($this->channel !== null) {
            return $this->channel;
        }

        $this->headerAdderInterceptor = new HeaderAddderInterceptor();

        $this->channel = new Channel($this->settings->getGrpcAddress(), [ # todo support security
            'credentials' => static::getCredentials($this->settings),
            'grpc.ssl_target_name_override' => $this->settings->serverName,
            //'grpc.keepalive_time_ms' => 1,
            //'grpc.keepalive_timeout_ms' => 1,
        ]);

        static::setupAuthInterceptor($this->headerAdderInterceptor, $this->settings);
        #todo setup db interceptor
        $this->channel = new InterceptorChannel($this->channel, $this->headerAdderInterceptor);

        $this->connectivityState = $this->channel->getConnectivityState(true);

        return $this->channel;
    }

    /**
     * @param Settings $settings
     *
     * @return ChannelCredentials|null
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

    public function getStub(string $stubClass): BaseStub
    {
        return new $stubClass(
            $this->getChannel()->getTarget(),
            [],
            $this->getChannel()
        );
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->settings->timeout;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function connected()
    {
        $this->connected = true;
    }

    public function disconnect()
    {
        $this->close();
        $this->connected = false;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->settings->user;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->settings->getGRPCAddress();
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setHeader(string $name, string $value)
    {
        $this->headerAdderInterceptor->setParameter($name, $value);
    }
}
