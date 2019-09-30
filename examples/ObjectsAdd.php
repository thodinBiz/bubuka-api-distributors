<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 15:09
 */

require_once 'init.php';

use Bubuka\Distributors\RestAPI\Exceptions\ApiErrorException;
use Bubuka\Distributors\RestAPI\Exceptions\BubukaException;
use Bubuka\Distributors\RestAPI\Exceptions\ResponseException;

$place = [
    'id'                    => OBJECT_ID,
    'name'                  => 'Gusli Office',
    'country'               => 'Россия',
    'city'                  => 'Санкт-Петербург',
    'address'               => 'Ленинский проспект 3 лит А',
    'subscriptionStartDate' => date('Y-m-d'),
    'subscriptionEndDate'   => date('Y-m-d', time() + 86400),
];

try {
    if ($apiClient->ObjectsAdd($place['id'], $place['name'], $place['country'], $place['city'], $place['address'], $place['subscriptionStartDate'], $place['subscriptionEndDate'])) {
        echo "\nObject was created\n";
    }
} catch (BubukaException $e) {
    // Api returned structure of error
    echo 'BubukaException: ' . $e->getMessage() . "\n";
}