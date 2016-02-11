<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:22
 */
namespace Sowbiba\CommandWatcherBundle\Reader;


abstract class AbstractReader implements ReaderInterface
{

    /**
     * @var array
     */
    private $listenedCommands;

    /**
     * @param array $listenedCommands
     */
    public function __construct(
        array $listenedCommands
    )
    {
        $this->listenedCommands     = $listenedCommands;
    }

    public function getCommands()
    {
        return $this->listenedCommands;
    }

    public function getDurationLogs($command)
    {
        $logs = [];
        foreach ($this->getLogs($command) as $log) {
            $logs[date("d/m/Y H:i:s", $log['start'])] = floatval($log['duration']);
        }

        return $logs;
    }

    public function getMemoryLogs($command)
    {
        $logs = [];
        foreach ($this->getLogs($command) as $log) {
            $logs[date("d/m/Y H:i:s", $log['start'])] = floatval($log['memory']);
        }

        return $logs;
    }
}