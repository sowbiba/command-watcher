<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 06/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandsStatsBundle\Listener;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class CommandListener
 * @package Sowbiba\CommandsStatsBundle\Listener
 *
 * @link http://symfony.com/doc/current/components/console/events.html
 * @link http://symfony.com/doc/current/components/stopwatch.html
 */
class CommandListener
{
    const LOG_PREFIX = 'sowbiba_command_';

    /**
     * @var Stopwatch
     */
    private $stopWatch;

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
     * @param Stopwatch $stopwatch
     */
    public function __construct(
        Stopwatch $stopwatch,
        array $listenedCommands,
        $logsPath,
        $logsPrefix
    )
    {
        $this->stopWatch            = $stopwatch;
        $this->listenedCommands     = $listenedCommands;
        $this->logsPath             = rtrim($logsPath, '/') . '/';
        $this->logsPrefix           = $logsPrefix;
    }

    public function onCommandStart(ConsoleCommandEvent $event)
    {
        // get the command to be executed
        $command = $event->getCommand();

        if (!in_array($command->getName(), $this->listenedCommands)) {
            // get the output instance
            // $output = $event->getOutput();
            // $output->writeln(sprintf('Application is not listened <info>%s</info>', $command->getName()));
            return;
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command->getName());

        $this->stopWatch->start($commandSlug);
    }



    public function onCommandEnd(ConsoleTerminateEvent $event)
    {
        // get the command to be executed
        $command = $event->getCommand();

        if (!in_array($command->getName(), $this->listenedCommands)) {
            return;
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command->getName());

        if ($this->stopWatch->isStarted($commandSlug)) {
            // get the output instance
            $output = $event->getOutput();

            $stopWatchEvent = $this->stopWatch->stop($commandSlug);

            $duration = $stopWatchEvent->getDuration() / 1000;
            $end = time();
            $start = $end-$duration;

            $memory = $stopWatchEvent->getMemory() / 1048576;     // Returns the max memory usage of all periods

            $output->writeln(sprintf("===== Command [ <info>%s</info> ] =====", $command->getName()));
            $output->writeln("");
            $output->writeln(sprintf("===   Start time : <info>%s</info>", date('d/m/Y H:i:s', $start)));
            $output->writeln(sprintf("===   End time   : <info>%s</info>", date('d/m/Y H:i:s', $end)));
            $output->writeln(sprintf("===   Duration   : <info>%s seconds</info>", $duration));
            $output->writeln(sprintf("===   Memory     : <info>%s Mb</info>", $memory));

            if (!is_dir($this->logsPath)) {
                throw new ContextErrorException(
                    sprintf("Directory [ %s ] does not exist", $this->logsPath), 0, E_USER_ERROR, $this->logsPath, __LINE__
                );
            }

            if (!is_writable($this->logsPath)) {
                throw new ContextErrorException(
                    sprintf("Directory [ %s ] is not writable", $this->logsPath), 0, E_USER_ERROR, $this->logsPath, __LINE__
                );
            }

            $filename = sprintf(
                "%s%s%s.log",
                $this->logsPath,
                $this->logsPrefix,
                $commandSlug
            );

            $logContent = sprintf(
                "%s;%s;%s;%s;%s\n",
                $start,
                $end,
                $duration,
                $memory,
                json_encode($input = $event->getInput()->getArguments())
            );

            if (! file_put_contents($filename, $logContent, FILE_APPEND | LOCK_EX)) {
                throw new IOException("Cannot write to $filename");
            }
        }
    }
} 