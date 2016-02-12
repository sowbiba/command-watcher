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
        if (!in_array($command, $this->listenedCommands)) {
            throw new \InvalidArgumentException("Command is not listened");
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command);

        $logs = [];
        foreach ($this->getLogs($commandSlug) as $log) {
            $logs[date("d/m/Y H:i:s", $log['start'])] = floatval($log['duration']);
        }

        return $logs;
    }

    public function getMemoryLogs($command)
    {
        if (!in_array($command, $this->listenedCommands)) {
            throw new \InvalidArgumentException("Command is not listened");
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command);

        $logs = [];
        foreach ($this->getLogs($commandSlug) as $log) {
            $logs[date("d/m/Y H:i:s", $log['start'])] = floatval($log['memory']);
        }

        return $logs;
    }
}