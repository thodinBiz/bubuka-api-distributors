Простенькая библиотека для взаимодествия с 
BUBUKA API for Distributors

https://test.my.bubuka.info/api/dst/doc.html

**Composer Installation**

```shell script
composer require thodinbiz/bubuka-api-for-distributors
```

**Usage examples**

```php

require_once './vendor/autoload.php';

use Bubuka\Distributors\RestAPI\Exceptions\ApiErrorException;
use Bubuka\Distributors\RestAPI\Exceptions\ResponseException;
use Bubuka\Distributors\RestAPI\ApiClient;

const API_URL = 'http://test.my.bubuka.info/api/dst/';
const TOKEN = '2b3f2d3e2c01a60c234c393214a17133';

// $client = new ApiClient(TOKEN, API_URL);
$apiClient = new ApiClient(TOKEN);

try
{
    $filesList = $apiClient->FilesList();
} catch (ApiErrorException $e)
{
    // Api returned structure of error
} catch (ResponseException $e)
{
    // 500 error, server unavailable, etc.
}

var_dump($filesList);
```