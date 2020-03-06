<?php /** @noinspection PhpUnused */

namespace amocrmtech\client\cookies;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 *
 */
class Config extends Model
{
    /** @var string */
    public $subdomain;
    /** @var string */
    public $login;
    /** @var string */
    public $token;
    /** @var string */
    public $cookiesFile;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            ['subdomain', 'string'],
            ['subdomain', 'required'],

            ['login', 'string'],
            ['login', 'required'],

            ['token', 'string'],
            ['token', 'required'],

            ['cookiesFile', 'default', 'value' => '@runtime/amocrmtech/cookies_{subdomain}.bin'],
            ['cookiesFile', 'string'],
            ['cookiesFile', 'validateCookiesFile'],
            ['cookiesFile', 'required'],
        ];
    }

    /**
     * @param string $attr
     */
    public function validateCookiesFile($attr)
    {
        $this->$attr = strtr($this->$attr, ['{subdomain}' => $this->subdomain]);
        $this->$attr = FileHelper::normalizePath(Yii::getAlias($this->$attr), '/');
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'subdomain'   => 'Поддомен',
            'login'       => 'Логин',
            'token'       => 'Токен',
            'cookiesFile' => 'Файл с cookies',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeHints()
    {
        return [
            'subdomain'   => 'Поддомен вашего аккаунта в amocrm, например: "subdomain" из "subdomain.amocrm.ru"',
            'login'       => 'Ваш логин в amocrm, в настройках профиля называется "Email"',
            'token'       => 'Токен, в настройках профиля называется "API Ключ"',
            'cookiesFile' => 'Файл с cookies, для сохранения данных по аутентификации, истекает ~каждые 15 минут, автоматически перезаписывается',
        ];
    }
}