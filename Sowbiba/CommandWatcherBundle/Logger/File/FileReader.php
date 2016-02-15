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

    public function getCommands()
    {
        return $this->listenedCommands;
    }

    public function getLogs($command)
    {
        if (!in_array($command, $this->listenedCommands)) {
            throw new \InvalidArgumentException("Command is not listened");
        }

        $identifier = Parser::slugifyCommand($command);

        $filename = sprintf(
            "%s%scommand-watcher.log",
            $this->logsPath,
            $this->logsPrefix
        );

        if (file_exists($filename)) {
            $logs = json_decode(file_get_contents($filename), true);
        } else {
            $logs = array();
        }

        return isset($logs[$identifier]) ? $logs[$identifier] : array();
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