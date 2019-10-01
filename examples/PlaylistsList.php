<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 30.09.2019
 * Time: 16:51
 */

use Bubuka\Distributors\RestAPI\Exceptions\BubukaException;

require_once 'init.php';

$page = 1;
$limit = 10;

try {
    $playlistList = $apiClient->PlaylistsList($page, $limit);

    print_r($playlistList);
} catch (BubukaException $e) {
    // Api returned structure of error
    echo 'BubukaException: ' . $e->getMessage() . "\n";
}
