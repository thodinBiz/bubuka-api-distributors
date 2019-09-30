<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 15:42
 */

require_once 'init.php';

use Bubuka\Distributors\RestAPI\Exceptions\BubukaException;

try {

    var_dump($apiClient->ObjectsList());

} catch (BubukaException $e) {
    // Api returned structure of error
    echo 'BubukaException: ' . $e->getMessage() . "\n";
}

