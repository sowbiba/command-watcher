<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 07/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Reader;


class FileReader extends AbstractReader
{

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
     * @param $logsPath
     * @param $logsPrefix
     */
    public function __construct(
        array $listenedCommands,
        $logsPath,
        $logsPrefix
    )
    {
        $this->logsPath             = rtrim($logsPath, '/') . '/';
        $this->logsPrefix           = $logsPrefix;

        parent::__construct($listenedCommands);
    }

    private function getFile($command)
    {
        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command);

        return sprintf(
            "%s%s%s.log",
            $this->logsPath,
            $this->logsPrefix,
            $commandSlug
        );
    }

    public function getLogs($command)
    {
        $logs = [];
        foreach (file($this->getFile($command)) as $line) {
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
} 