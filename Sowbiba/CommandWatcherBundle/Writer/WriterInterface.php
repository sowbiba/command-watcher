<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:30
 */

namespace Sowbiba\CommandWatcherBundle\Writer;


interface WriterInterface
{
    public function write(array $log, $identifier);
}