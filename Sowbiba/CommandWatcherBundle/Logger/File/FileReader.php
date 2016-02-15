<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 07/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Logger\File;


use Sowbiba\CommandWatcherBundle\Logger\Parser;
use Sowbiba\CommandWatcherBundle\Logger\ReaderInterface;

class FileReader implements ReaderInterface
{
    /**
     * @var array
     */
    private $listenedCommands;

    /**
     * @var string
     */
    private $logsPath;

    /**
     * @var string
     */
    private $logsPrefix;

    /**
     * @param array $listenedCommands
     * @param string $logsPath
     * @param string $logsPrefix
     */
    public function __construct(
        array $listenedCommands,
        $logsPath,
        $logsPrefix
    )
    {
        $this->logsPath             = rtrim($logsPath, '/') . '/';
        $this->logsPrefix           = $logsPrefix;
        $this->listenedCommands     = $listenedCommands;
    }

    public function getLogs($command)
    {
        if (!in_array($command, $this->listenedCommands)) {
            throw new \InvalidArgumentException("Command is not listened");
        }

        $filename = sprintf(
            "%s%s%s.log",
            $this->logsPath,
            $this->logsPrefix,
            Parser::slugifyCommand($command)
        );

        $logs = [];
        foreach (file($filename) as $line) {
            $lineData = explode(";", $line);
            /**
             * $start,
             * $end,
             * $duration,
             * $memory,
             * json_encode($input = $event->getInput()->getArguments())
             */
            $logs[] = [
                'start' => $lineData[0],
                'end' => $lineData[1],
                'duration' => $lineData[2],
                'memory' => $lineData[3],
                'arguments' => $lineData[4],
            ];
        }

        return $logs;
    }

    public function getDurationLogs($command)
    {
        return Parser::get($this->getLogs($command), 'duration');
    }

    public function getMemoryLogs($command)
    {
        return Parser::get($this->getLogs($command), 'memory');
    }
} 