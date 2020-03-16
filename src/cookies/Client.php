<?php

namespace amocrmtech\client\cookies;

use amocrmtech\client\exceptions\InvalidModelException;
use amocrmtech\client\helpers\ModelHelper;
use yii\base\InvalidConfigException;

/**
 *
 */
class Client extends \amocrmtech\client\Client
{
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
        $this->config  = ModelHelper::ensure($this->config, Config::class);
        $this->baseUrl = "https://{$this->config->subdomain}.amocrm.ru/api/v2";

    }
}