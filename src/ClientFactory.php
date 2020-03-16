<?php /** @noinspection PhpUnused */

namespace amocrmtech\client;

use amocrmtech\client\cookies\Client as ClientCookies;
use amocrmtech\client\cookies\Config as ConfigCookies;
use amocrmtech\client\cookies\Request as RequestCookies;
use amocrmtech\client\oauth\Client as ClientOAuth;
use amocrmtech\client\oauth\Config as ConfigOAuth;
use amocrmtech\client\oauth\Request as RequestOAuth;
use Yii;
use yii\httpclient\CurlTransport;

/**
 *
 */
class ClientFactory
{
    /**
     * @param ConfigOAuth|array $config
     *
     * @return ClientOAuth
     */
    public static function buildOAuth($config)
    {
        return self::lazyOAuth($config)();
    }

    /**
     * @param ConfigOAuth|array $config
     *
     * @return callable
     */
    public static function lazyOAuth($config)
    {
        return static function () use ($config) {
            return Yii::createObject([
                'class'         => ClientOAuth::class,
                'config'        => $config,
                'transport'     => CurlTransport::class,
                'requestConfig' => ['class' => RequestOAuth::class],
            ]);
        };
    }

    /**
     * @param ConfigCookies|array $config
     *
     * @return ClientCookies
     */
    public static function buildCookies($config)
    {
        return self::lazyCookies($config)();
    }

    /**
     * @param ConfigCookies|array $config
     *
     * @return callable
     */
    public static function lazyCookies($config)
    {
        return static function () use ($config) {
            return Yii::createObject([
                'class'         => ClientCookies::class,
                'config'        => $config,
                'transport'     => CurlTransport::class,
                'requestConfig' => ['class' => RequestCookies::class],
            ]);
        };
    }
}