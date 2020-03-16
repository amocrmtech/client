<?php

namespace amocrmtech\client\cookies;

use amocrmtech\client\Client;
use amocrmtech\client\exceptions\InvalidModelException;
use amocrmtech\client\helpers\ModelHelper;
use RuntimeException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 *
 */
class Request extends \yii\httpclient\Request
{
    const EVENT_COOKIES_REFRESHED = 'cookiesRefreshed';

    /** @var Client */
    public $client;
    /** @var Config */
    public $config;

    /**
     * {@inheritDoc}
     * @throws InvalidModelException
     * @throws InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config = ModelHelper::ensure($this->config, Config::class);

        $this->client->baseUrl = "https://{$this->config->subdomain}.amocrm.ru/api/v2";

    }

    /**
     * @return Response
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function send()
    {
        $this->addOptions(['cookieFile' => $this->config->cookiesFile]);

        $response = parent::send();

        /** @noinspection TypeUnsafeComparisonInspection */
        if ($response->statusCode == 401) {
            $this->refreshCookies();
            $response = parent::send();
        }

        return $response;
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    protected function refreshCookies()
    {
        $config = $this->config;
        $client = new \yii\httpclient\Client([
            'baseUrl'   => "https://{$config->subdomain}.amocrm.ru",
            'transport' => CurlTransport::class,
        ]);

        $request = $client->post(['private/api/auth.php', 'type' => 'json'], [
            'USER_LOGIN' => $config->login,
            'USER_HASH'  => $config->token,
        ]);

        FileHelper::createDirectory(dirname($config->cookiesFile));
        $request->addOptions(['cookieJar' => $config->cookiesFile]);

        $response = $request->send();
        if (!$response->isOk) {
            throw new RuntimeException("Error during cookies refreshing: {$response->content}");
        }

        $this->trigger(self::EVENT_COOKIES_REFRESHED, new EventCookiesRefreshed([
            'request'  => $this,
            'response' => $response,
            'file'     => $config->cookiesFile,
        ]));
    }
}