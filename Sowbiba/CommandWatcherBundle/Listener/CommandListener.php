<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 06/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Listener;

use Sowbiba\CommandWatcherBundle\Logger\WriterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Class CommandListener
 * @package Sowbiba\CommandWatcherBundle\Listener
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
     * @var WriterInterface
     */
    private $writer;

    public function __construct(
        Stopwatch $stopwatch,
        WriterInterface $writer,
        array $listenedCommands
    )
    {
        $this->stopWatch            = $stopwatch;
        $this->writer               = $writer;
        $this->listenedCommands     = $listenedCommands;
    }

    public function onCommandStart(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        if (!in_array($command->getName(), $this->listenedCommands)) {
            return;
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command->getName());

        $this->stopWatch->start($commandSlug);
    }



    public function onCommandEnd(ConsoleTerminateEvent $event)
    {
        $command = $event->getCommand();

        if (!in_array($command->getName(), $this->listenedCommands)) {
            return;
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command->getName());

        if ($this->stopWatch->isStarted($commandSlug)) {
            $stopWatchEvent = $this->stopWatch->stop($commandSlug);

            $this->writer->write(
                $this->extractLog($stopWatchEvent, $event->getOutput(), $command, $event->getInput()->getArguments()),
                $commandSlug
            );
        }
    }

    private function extractLog(StopwatchEvent $stopWatchEvent, OutputInterface $output, Command $command, array $arguments)
    {
        $duration = $stopWatchEvent->getDuration() / 1000;
        $end = time();
        $start = $end-$duration;

        $memory = $stopWatchEvent->getMemory() / 1048576;     // Returns the max memory usage of all periods

        $log = array(
            'start' => $start,
            'end' => $end,
            'duration' => $duration,
            'memory' => $memory,
            'arguments' => json_encode($arguments)
        );

        $output->writeln(sprintf("===== Command [ <info>%s</info> ] =====", $command->getName()));
        $output->writeln("");
        $output->writeln(sprintf("===   Start time : <info>%s</info>", date('d/m/Y H:i:s', $log['start'])));
        $output->writeln(sprintf("===   End time   : <info>%s</info>", date('d/m/Y H:i:s', $log['end'])));
        $output->writeln(sprintf("===   Duration   : <info>%s seconds</info>", $log['duration']));
        $output->writeln(sprintf("===   Memory     : <info>%s Mb</info>", $log['memory']));

        return $log;
    }
} 