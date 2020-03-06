<?php
namespace amocrmtech\client\oauth;

use RuntimeException;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\httpclient\Response;

/**
 *
 */
class Request extends \yii\httpclient\Request
{
    const EVENT_ACCESS_TOKEN_REFRESHED = 'accessTokenRefreshed';

    /**
     * @var Client owner client instance.
     */
    public $client;

    /**
     * @return Response
     * @throws Exception
     */
    public function send()
    {
        $this->addHeaders(['Authorization' => "Bearer {$this->client->config->accessToken}"]);

        $response = parent::send();

        if (!$response->isOk && $response->statusCode === 401) {
            $credentials                       = $this->refreshCredentials();
            $this->client->config->accessToken = $credentials->access_token;

            $this->headers->remove('Authorization');
            $this->addHeaders(['Authorization' => "Bearer {$this->client->config->accessToken}"]);
            $response = parent::send();
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    protected function refreshCredentials()
    {
        $config = $this->client->config;
        $client = new \yii\httpclient\Client([
            'baseUrl'   => "https://{$config->subdomain}.amocrm.ru",
            'transport' => CurlTransport::class,
        ]);

        $request = $client->post(['oauth2/access_token'], [
            'grant_type'    => 'refresh_token',
            'client_id'     => $config->integrationId,
            'client_secret' => $config->secretKey,
            'refresh_token' => $config->refreshToken,
            'redirect_uri'  => $config->redirectUri,
        ]);

        $response = $request->send();

        if (!$response->isOk) {
            throw new RuntimeException("Error during access_token refreshing: {$response->content}");
        }
        if ($response->data['expires_in'] <= 0) {
            throw new RuntimeException("Token's expires_in parameter is below zero");
        }

        $config->accessToken = $response->data['access_token'];

        $this->trigger(self::EVENT_ACCESS_TOKEN_REFRESHED, new EventAccessTokenRefreshed([
            'request'     => $this,
            'response'    => $response,
            'credentials' => $response->data,
        ]));
    }
}