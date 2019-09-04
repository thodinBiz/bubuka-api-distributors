<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 03.09.2019
 * Time: 22:03
 */

namespace Bubuka\Distributors\RestAPI;

interface ApiClientInterface
{
    public function __construct($token, $apiBaseURL);

    public function FileList($page = 1, $limit = 100);

    public function UsersList($page = 1, $limit = 100);

    public function UsersGet($id);

    public function UsersAdd($id, $name, $subscriptionStartDate, $subscriptionEndDate);

    public function UsersUpdate($id, $name, $subscriptionStartDate, $subscriptionEndDate);

    public function UsersStatistics($statistics);

    public function ObjectsList($page = 1, $limit = 100);

    public function ObjectsGet($id);

    public function ObjectsAdd($id, $name, $country, $city, $address, $subscriptionStartDate, $subscriptionEndDate);

    public function ObjectsUpdate($id, $name, $country, $city, $address, $subscriptionStartDate, $subscriptionEndDate);

    public function ObjectsStatistics($statistics);
}
