<?php

namespace amocrmtech\client\cookies;

use RuntimeException;
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

    /**
     * @return Response
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function send()
    {
        $this->addOptions(['cookieFile' => $this->client->config->cookiesFile]);

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
    private function refreshCookies()
    {
        $config = $this->client->config;
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