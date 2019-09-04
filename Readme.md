Простенькая библиотека для взаимодествия с 
BUBUKA API for Distributors 0.1.0

https://test.my.bubuka.info/api/dst/doc.html

**Usage examples**

```$xslt

use Bubuka\Distributors\RestAPI\Exceptions\ApiErrorException;
use Bubuka\Distributors\RestAPI\Exceptions\ResponseException;
use Bubuka\Distributors\RestAPI\ApiClient;

const API_URL = 'http://test.my.bubuka.info/api/dst/';
const TOKEN = '2b3f2d3e2c01a60c234c393214a17133';

// $client = new ApiClient(TOKEN, API_URL);
$apiClient = new ApiClient(TOKEN);

try{
    $filsList = $apiClient->FileList();
} catch ($e ApiErrorException)
{
    // Api returned structure of error 
} catch ($e ResponseException)
{
    // 500 error, server unavailable, etc.
}

var_dump($fileList);
```