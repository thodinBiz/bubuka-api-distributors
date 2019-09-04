<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 15:21
 */

require_once 'init.php';

const RECORDS_COUNT = 200;

$files = $apiClient->FilesList();

$statistic = [];

for ($i = 0; $i < RECORDS_COUNT; ++$i) {
    $file = $this->first100Files[array_rand($this->first100Files)];

    $row = new \stdClass();
    $row->file_id = $file->id;
    $row->object_id = OBJECT_ID;
    //$row->playback_time = 150;
    $row->datetime = date('Y-m-d H:i:s', time() - rand(0, 1000000));

    $statistic[] = $row;
}

$this->apiClient->ObjectsStatistics($statistic);
