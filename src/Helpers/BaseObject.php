<?php
/**
 * Created by PhpStorm.
 * User: Thodin
 * Date: 01.10.2019
 * Time: 19:37
 */

namespace Bubuka\Distributors\RestAPI\Helpers;

/**
 * Class BaseObject
 * @package Bubuka\Distributors\RestAPI\Helpers
 */
class BaseObject
{
    /**
     * BaseObject constructor.
     *
     * @param iterable|object $data
     */
    public function __construct($data = null)
    {
        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }
    }
}
