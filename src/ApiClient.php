<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 03.09.2019
 * Time: 15:46
 */

namespace Bubuka\Distributors\RestAPI;

use Bubuka\Distributors\RestAPI\Exceptions\ApiErrorException;
use Bubuka\Distributors\RestAPI\Exceptions\ResponseException;

use stdClass;

/**
 * Class ApiClient
 * @package Bubuka\Distributors\RestAPI
 */
class ApiClient implements ApiClientInterface
{
    const API_URL = 'http://test.my.bubuka.info/api/dst/';

    const PATH_FILES_LIST = 'files/list';

    const PATH_USERS_LIST = 'users/list';
    const PATH_USERS_GET = 'users/get/';
    const PATH_USERS_ADD = 'users/add';
    const PATH_USERS_UPDATE = 'users/update/';
    const PATH_USERS_STATISTICS = 'users/statistics';

    const PATH_OBJECTS_LIST = 'objects/list';
    const PATH_OBJECTS_GET = 'objects/get/';
    const PATH_OBJECTS_ADD = 'objects/add';
    const PATH_OBJECTS_UPDATE = 'objects/update/';
    const PATH_OBJECTS_STATISTICS = 'objects/statistics';

    const HTTP_OK = 200;
    const REQUEST_GET = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_PUT = 'PUT';
    const REQUEST_DELETE = 'DELETE';

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $token;

    /**
     * ApiClient constructor.
     *
     * @param string $token
     * @param string $apiBaseURL
     */
    public function __construct($token, $apiBaseURL = null)
    {
        $this->token = $token;
        $this->apiUrl = $apiBaseURL ?: self::API_URL;
    }

    /**
     * Form and send request to API service
     * public for easy tests
     *
     * @param string $path
     * @param string $method
     * @param array  $data
     * @param bool   $useToken
     *
     * @return stdClass
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function sendRequest($path, $method = self::REQUEST_GET, $data = [], $useToken = true)
    {
        $url = $this->apiUrl . '/' . $path;
        $method = strtoupper($method);
        $curl = curl_init();

        if ($useToken && !empty($this->token)) {
            $headers = ['Token: ' . $this->token];
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        switch ($method) {
            case self::REQUEST_POST:
                if (is_array($data)) {
                    curl_setopt($curl, CURLOPT_POST, count($data));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                } elseif (is_string($data)) {
                    curl_setopt($curl, CURLOPT_POST, strlen($data));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case self::REQUEST_PUT:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::REQUEST_POST);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case self::REQUEST_DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::REQUEST_DELETE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            default:
                if (!empty($data)) {
                    $url .= '?' . http_build_query($data);
                }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseBody = substr($response, $header_size);
        curl_close($curl);

        $retVal = new stdClass();
        $retVal->data = json_decode($responseBody);
        $retVal->http_code = $headerCode;

        $this->validateResponse($retVal);

        return $retVal;
    }

    /**
     * public for easy tests
     *
     * @param stdClass $retVal
     *
     * @return bool
     * @throws ApiErrorException
     * @throws ResponseException
     */
    public function validateResponse($retVal)
    {
        if (isset($retVal->data->success) && $retVal->data->success === true) {
            return true;
        }

        if ($retVal->http_code !== self::HTTP_OK) {
            throw new ResponseException('Server return bad http code ' . $retVal->http_code, 500);
        }

        if (isset($retVal->data->error, $retVal->data->error->code, $retVal->data->error->message)) {
            throw new ApiErrorException($retVal->data->error->message, $retVal->data->error->code);
        } else {
            throw new ResponseException('Server return success ' . var_dump($retVal->data->success)
                . ' but not present error structure', 500);
        }
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#FilesList
     *
     * @param int $page
     * @param int $limit
     *
     * @return stdClass
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function FileList($page = 1, $limit = 100)
    {
        $response = $this->sendRequest(self::PATH_FILES_LIST, self::REQUEST_GET,
            [
                'page'  => $page,
                'limit' => $limit,
            ]);

        if (isset($response->data->result->files)) {
            return $response->data->result;
        } else {
            throw new ResponseException('Invalid response format');
        }

    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#UsersList
     *
     * @param int $page
     * @param int $limit
     *
     * @return mixed
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function UsersList($page = 1, $limit = 100)
    {
        $response = $this->sendRequest(self::PATH_USERS_LIST, self::REQUEST_GET,
            [
                'page'  => $page,
                'limit' => $limit,
            ]);

        if (isset($response->data->result->users)) {
            return $response->data->result;
        } else {
            throw new ResponseException('Invalid response format');
        }
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#UsersGet
     *
     * @param string $id
     *
     * @return mixed
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function UsersGet($id)
    {
        $response = $this->sendRequest(self::PATH_USERS_GET . $id, self::REQUEST_GET);

        if (isset($response->data->result->user)) {
            return $response->data->user;
        } else {
            throw new ResponseException('Invalid response format');
        }
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#UsersAdd
     *
     * @param string|int $id
     * @param string     $name
     * @param string     $subscriptionStartDate
     * @param string     $subscriptionEndDate
     *
     * @return bool
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function UsersAdd($id, $name, $subscriptionStartDate, $subscriptionEndDate)
    {
        $response = $this->sendRequest(self::PATH_USERS_ADD, self::REQUEST_POST, [
            'id'                      => $id,
            'name'                    => $name,
            'subscription_start_date' => $subscriptionStartDate,
            'subscription_end_date'   => $subscriptionEndDate,
        ]);

        return $response->data->success;
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#UsersUpdate
     *
     * @param string|int $id
     * @param string     $name
     * @param string     $subscriptionStartDate
     * @param string     $subscriptionEndDate
     *
     * @return bool
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function UsersUpdate($id, $name, $subscriptionStartDate, $subscriptionEndDate)
    {
        $response = $this->sendRequest(self::PATH_USERS_UPDATE . $id, self::REQUEST_POST, [
            'name'                    => $name,
            'subscription_start_date' => $subscriptionStartDate,
            'subscription_end_date'   => $subscriptionEndDate,
        ]);

        return $response->data->success;
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#UsersStatistics
     *
     * @param array $statistics array of
     * { file_id: 21453, user_id: N7yMF1, playback_time: 237, datetime: 2019-09-03 09:01:05 }
     *
     * @return bool
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function UsersStatistics($statistics)
    {
        $response = $this->sendRequest(self::PATH_USERS_STATISTICS, self::REQUEST_POST,
            json_encode($statistics, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $response->data->success;
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#ObjectsList
     *
     * @param int $page
     * @param int $limit
     *
     * @return mixed
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function ObjectsList($page = 1, $limit = 100)
    {
        $response = $this->sendRequest(self::PATH_OBJECTS_LIST, self::REQUEST_GET,
            [
                'page'  => $page,
                'limit' => $limit,
            ]);

        if (isset($response->data->result->objects)) {
            return $response->data->result;
        } else {
            throw new ResponseException('Invalid response format');
        }
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#ObjectsGet
     *
     * @param string $id
     *
     * @return mixed
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function ObjectsGet($id)
    {
        $response = $this->sendRequest(self::PATH_OBJECTS_GET . $id, self::REQUEST_GET);

        if (isset($response->data->result->object)) {
            return $response->data->object;
        } else {
            throw new ResponseException('Invalid response format');
        }
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#ObjectsAdd
     *
     * @param string|int $id
     * @param string     $name
     * @param string     $country
     * @param string     $city
     * @param string     $address
     * @param string     $subscriptionStartDate
     * @param string     $subscriptionEndDate
     *
     * @return bool
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function ObjectsAdd($id, $name, $country, $city, $address, $subscriptionStartDate, $subscriptionEndDate)
    {
        $response = $this->sendRequest(self::PATH_OBJECTS_ADD, self::REQUEST_POST, [
            'id'                      => $id,
            'name'                    => $name,
            'country'                 => $country,
            'city'                    => $city,
            'address'                 => $address,
            'subscription_start_date' => $subscriptionStartDate,
            'subscription_end_date'   => $subscriptionEndDate,
        ]);

        return $response->data->success;
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#ObjectsUpdate
     *
     * @param string|int $id
     * @param string     $name
     * @param string     $country
     * @param string     $city
     * @param string     $address
     * @param string     $subscriptionStartDate
     * @param string     $subscriptionEndDate
     *
     * @return bool
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function ObjectsUpdate($id, $name, $country, $city, $address, $subscriptionStartDate, $subscriptionEndDate)
    {
        $response = $this->sendRequest(self::PATH_OBJECTS_UPDATE . $id, self::REQUEST_POST, [
            'id'                      => $id,
            'name'                    => $name,
            'country'                 => $country,
            'city'                    => $city,
            'address'                 => $address,
            'subscription_start_date' => $subscriptionStartDate,
            'subscription_end_date'   => $subscriptionEndDate,
        ]);

        return $response->data->success;
    }

    /**
     * @see http://test.my.bubuka.info/api/dst/doc.html#ObjectsStatistics
     *
     * @param array $statistics array of
     * { file_id: 21453, object_id: N7yMF1, playback_time: 237, datetime: 2019-09-03 09:01:05 }
     *
     * @return bool
     * @throws ResponseException
     * @throws ApiErrorException
     */
    public function ObjectsStatistics($statistics)
    {
        $response = $this->sendRequest(self::PATH_OBJECTS_STATISTICS, self::REQUEST_POST,
            json_encode($statistics, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $response->data->success;
    }
}
