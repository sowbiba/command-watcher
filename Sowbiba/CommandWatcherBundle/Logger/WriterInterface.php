<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:30
 */

namespace Sowbiba\CommandWatcherBundle\Logger;


interface WriterInterface
{
    /**
     * @param array $log
     * @param $identifier
     * @return mixed
     */
    public function write(array $log, $identifier);
}