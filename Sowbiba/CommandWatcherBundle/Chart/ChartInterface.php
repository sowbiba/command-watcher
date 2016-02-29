<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 15/02/16
 * Time: 12:48
 */

namespace Sowbiba\CommandWatcherBundle\Chart;


interface ChartInterface
{
    /**
     * @param $command
     * @param $category
     * @param array $logs
     * @param $unit
     *
     * @return mixed
     */
    public static function get($command, $category, array $logs);
}