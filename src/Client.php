<?php

namespace amocrmtech\client;

use Yii;
use yii\base\InvalidConfigException;
use yii\di\ServiceLocator;

/**
 *
 */
class Client extends \yii\httpclient\Client
{
    /** @var ServiceLocator */
    public $locator;
    /** @var array[] */
    public $components = [];

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->locator = Yii::createObject([
            'class'      => ClientServiceLocator::class,
            'components' => $this->components,
        ], [$this]);
    }

    /**
     * {@inheritDoc}
     * @throws InvalidConfigException
     */
    public function __get($name)
    {
        if ($this->locator->has($name)) {
            return $this->locator->get($name);
        }

        return parent::__get($name);
    }
}