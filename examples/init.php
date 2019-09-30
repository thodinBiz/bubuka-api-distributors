<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 15:09
 */

require_once './../vendor/autoload.php';

use Bubuka\Distributors\RestAPI\ApiClient;

const API_URL = 'http://test.my.bubuka.info/api/dst/';
const TOKEN = '2b3f2d3e2c01a60c234c393214a17133';
const OBJECT_ID = 'gusliTestPlace';

$apiClient = new ApiClient(TOKEN, API_URL);
