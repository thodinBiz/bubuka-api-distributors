<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 04.09.2019
 * Time: 15:21
 */

require_once 'init.php';

use Bubuka\Distributors\RestAPI\ApiClient;

/**
 * Class sendStatistic
 */
class sendStatistic
{

    /**
     * @var ApiClient
     */
    protected $apiClient;
    protected $first100Files = [];

    public function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function run()
    {
        $this->getFilesList();

        $statistic = [];

        for ($i = 0; $i < 200; ++$i)
        {
            $statistic[] = $this->generateStatisticRow(OBJECT_ID);
        }

        $this->sendStatistic($statistic);

    }

    public function sendStatistic($statistic)
    {
        $this->apiClient->ObjectsStatistics($statistic);
    }

    public function generateStatisticRow($objectId)
    {
        $file = $this->first100Files[array_rand($this->first100Files)];

        $ret = new \stdClass();
        $ret->file_id = $file->id;
        $ret->object_id = $objectId;
        //$ret->playback_time = 150;
        $ret->datetime = date('Y-m-d H:i:s', time() - rand(0, 1000000));

        return $ret;
    }

    public function getFilesList()
    {

        $response = $this->apiClient->FileList();

        $this->first100Files = $response->files;

    }
}


(new sendStatistic($apiClient))->run();
