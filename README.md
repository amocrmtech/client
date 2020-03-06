### Создание клиента для работы через старый flow (логин/токен)
Между запросами будет создаваться файл с cookies.  
Устаревает ~ за 15 минут, потом пересоздается обновленный

```php
$client = ClientFactory::buildCookies([
    'subdomain'   => 'your_subdomain',
    'login'       => 'your_login',
    'token'       => 'your_token',
    'cookiesFile' => '@runtime/amo/cookies_{subdomain}.bin', // не обязательно, по умолчанию - такой
]);

$request = $client->get(['account']);
$response = $request->send();
$data = $response->data;
```

### Создание клиента для работы через oauth
Вопрос получения refreshToken-а тут не рассматривается

```php
$client   = ClientFactory::buildOAuth([
    'subdomain'     => 'your_subdomain',
    'accessToken'   => 'your_access_token', // не обязательно, будет получен при запросе
    'refreshToken'  => 'your_refresh_token',
    'redirectUri'   => 'your_redirect_uri',
    'integrationId' => 'your_integration_id',
    'secretKey'     => 'your_secret_key',
]);

$request = $client->get(['account']);
$response = $request->send();
$data = $response->data;
```
