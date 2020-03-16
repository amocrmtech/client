<?php

namespace amocrmtech\client\oauth;

use yii\base\Model;

/**
 *
 */
class Config extends Model
{
    /** @var string */
    public $subdomain;
    /** @var string */
    public $accessToken;
    /** @var string */
    public $refreshToken;
    /** @var string */
    public $redirectUri;
    /** @var string */
    public $integrationId;
    /** @var string */
    public $secretKey;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['subdomain', 'string'],
            ['subdomain', 'required'],

            ['accessToken', 'string'],

            ['refreshToken', 'string'],
            ['refreshToken', 'required'],

            ['redirectUri', 'string'],
            ['redirectUri', 'required'],

            ['integrationId', 'string'],
            ['integrationId', 'required'],

            ['secretKey', 'string'],
            ['secretKey', 'required'],
        ];
    }
}