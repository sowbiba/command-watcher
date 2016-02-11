<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:22
 */
namespace Sowbiba\CommandWatcherBundle\Reader;


interface ReaderInterface
{
    public function getCommands();

    public function getLogs($command);

    public function getDurationLogs($command);

    public function getMemoryLogs($command);
}