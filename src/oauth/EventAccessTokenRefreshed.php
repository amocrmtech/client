<?php
namespace amocrmtech\client\oauth;

use yii\base\Event;
use yii\httpclient\Response;

/**
 *
 */
class EventAccessTokenRefreshed extends Event
{
    /** @var Request */
    public $request;
    /** @var Response */
    public $response;
    /** @var array */
    public $credentials;
}