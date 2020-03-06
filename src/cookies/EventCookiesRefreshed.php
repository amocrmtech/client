<?php
namespace amocrmtech\client\cookies;

use yii\base\Event;
use yii\httpclient\Response;

/**
 *
 */
class EventCookiesRefreshed extends Event
{
    /** @var Request */
    public $request;
    /** @var Response */
    public $response;
    /** @var string */
    public $file;
}