<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 30.09.2019
 * Time: 16:51
 */

use Bubuka\Distributors\RestAPI\Exceptions\BubukaException;

require_once 'init.php';

const PLAYLIST_ID = 1;
$page = 1;
$limit = 2;

try {
    $response = $apiClient->PlaylistsGet(PLAYLIST_ID, $page, $limit);

    print_r($response);

} catch (BubukaException $e) {
    // Api returned structure of error
    echo 'BubukaException: ' . $e->getMessage() . "\n";
}
