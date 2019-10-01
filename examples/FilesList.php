<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 14:52
 */

require_once 'init.php';

use Bubuka\Distributors\RestAPI\Exceptions\BubukaException;

$page = 1;
$limit = 501;

try {
    $filesList = $apiClient->FilesList($page, $limit);

    print_r($filesList);

    echo "\nFiles on page\n" . count($filesList->files);

} catch (BubukaException $e) {
    // Api returned structure of error
    echo 'BubukaException: ' . $e->getMessage() . "\n";
}
