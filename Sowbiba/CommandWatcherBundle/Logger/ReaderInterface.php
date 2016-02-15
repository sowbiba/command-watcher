<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:22
 */
namespace Sowbiba\CommandWatcherBundle\Logger;


interface ReaderInterface
{
    /**
     * @return array
     */
    public function getCommands();

    /**
     * @param $command
     *
     * @return array
     */
    public function getLogs($command);

    /**
     * @param $command
     *
     * @return array
     */
    public function getDurationLogs($command);

    /**
     * @param $command
     *
     * @return array
     */
    public function getMemoryLogs($command);
}