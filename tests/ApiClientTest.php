<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 03.09.2019
 * Time: 20:01
 */

namespace Bubuka\Distributors\RestAPI\Tests;

use Bubuka\Distributors\RestAPI\ApiClient;
use PHPUnit\Framework\TestCase;
use Bubuka\Distributors\RestAPI\Exceptions\ApiErrorException;
use Bubuka\Distributors\RestAPI\Exceptions\ResponseException;


/**
 * Class HttpClientTest
 * @package Bubuka\Distributors\RestAPI\Tests
 */
class ApiClientTest extends TestCase
{

    /**
     *
     */
    const API_URL = 'http://test.my.bubuka.info/api/dst/';

    /**
     *
     */
    const TOKEN = '2b3f2d3e2c01a60c234c393214a17133';

    /**
     * @throws ApiErrorException
     * @throws ResponseException
     */
    public function testCheckResponse()
    {
        $client = new ApiClient(self::TOKEN, self::API_URL);
        $response = $client->sendRequest(ApiClient::PATH_FILES_LIST, ApiClient::REQUEST_GET);

        $this->assertArrayHasKey('files', (array)$response->data->result);
        $this->assertArrayHasKey('page', (array)$response->data->result);
        $this->assertArrayHasKey('limit', (array)$response->data->result);
        $this->assertArrayHasKey('count', (array)$response->data->result);
        $this->assertArrayHasKey('total_count', (array)$response->data->result);
    }

    /**
     * @throws ApiErrorException
     * @throws ResponseException
     */
    public function testCheckValidationTrue()
    {
        $retVal = new \stdClass();
        $retVal->data = new \stdClass();
        $retVal->http_code = 200;
        $retVal->data->success = true;

        $client = new ApiClient(self::TOKEN, self::API_URL);
        $this->assertTrue($client->validateResponse($retVal));
    }

    /**
     * @throws ResponseException
     */
    public function testCheckValidation()
    {
        $retVal = new \stdClass();
        $retVal->data = new \stdClass();
        $retVal->http_code = 200;
        $client = new ApiClient(self::TOKEN, self::API_URL);

        $retVal->data->success = false;
        $retVal->data->error = new \stdClass();
        $retVal->data->error->code = 999;
        $retVal->data->error->message = 'Test Message';

        try {
            $client->validateResponse($retVal);
        } catch (ApiErrorException $e) {
            $this->assertTrue($e->getMessage() === $retVal->data->error->message && $e->getCode() === 999);
        }

        $this->assertTrue(false);
    }
}
