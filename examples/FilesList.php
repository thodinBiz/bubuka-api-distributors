<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 14:52
 */

require_once 'init.php';

use Bubuka\Distributors\RestAPI\Exceptions\ApiErrorException;
use Bubuka\Distributors\RestAPI\Exceptions\ResponseException;

$page = 1;
$limit = 501;

try {
    $filesList = $apiClient->FileList($page, $limit);
} catch (ApiErrorException $e) {
    // Api returned structure of error
} catch (ResponseException $e) {
    // 500 error, server unavailable, etc.
}

foreach ($filesList->files as $file) {
    var_dump($file);
}


echo "\nFiles on page\n" . count($filesList->files);