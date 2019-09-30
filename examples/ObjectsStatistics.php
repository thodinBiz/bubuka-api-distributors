<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 15:21
 */

use Bubuka\Distributors\RestAPI\Requests\ObjectsStatisticsRequest;

require_once 'init.php';

const RECORDS_COUNT = 200;

$files = $apiClient->FilesList(1, 10);
$statistic = [];

for ($i = 0; $i < RECORDS_COUNT; ++$i) {
    $file = $files->files[array_rand($files->files)];

    $row = new \stdClass();
    $row->file_id = $file->id;
    $row->object_id = OBJECT_ID;
    $row->datetime = date('Y-m-d H:i:s', time() - rand(0, 1000000));

    $statistic[] = $row;
}

var_dump($apiClient->ObjectsStatistics($statistic));
