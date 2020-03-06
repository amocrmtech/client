<?php
namespace amocrmtech\client\oauth;

use amocrmtech\client\exceptions\InvalidModelException;
use amocrmtech\client\helpers\ModelHelper;
use yii\base\InvalidConfigException;

/**
 *
 */
class Client extends \yii\httpclient\Client
{
    /** @var Config */
    public $config;

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     * @throws InvalidModelException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->config  = ModelHelper::ensure($this->config, Config::class);
        $this->baseUrl = "https://{$this->config->subdomain}.amocrm.ru/api/v2";
    }
}