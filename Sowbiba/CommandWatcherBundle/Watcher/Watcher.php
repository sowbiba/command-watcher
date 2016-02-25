<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 25/02/16
 * Time: 15:49
 */

namespace Sowbiba\CommandWatcherBundle\Watcher;


use Sowbiba\CommandWatcherBundle\Logger\WriterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

final class Watcher
{
    /**
     * @var Stopwatch
     */
    private $stopWatch;

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * @param WriterInterface $writer
     */
    public function __construct(
        WriterInterface $writer
    )
    {
        $this->writer               = $writer;
        $this->stopWatch = new Stopwatch();
    }

    /**
     * Starts the watcher.
     *
     * @param $identifier
     */
    public function start($identifier)
    {
        $this->stopWatch->start($identifier);
    }

    /**
     * Ends the watch, extract logs and write them to the storage service chosen.
     * @param $identifier
     * @param OutputInterface|null $output
     * @param InputInterface $input
     *
     * @return array
     */
    public function end($identifier, OutputInterface $output = null, InputInterface $input)
    {
        $logs = array();

        if ($this->stopWatch->isStarted($identifier)) {
            $stopWatchEvent = $this->stopWatch->stop($identifier);

            $extra = null !== $input ? $input->getArguments() : array();

            $logs = $this->extractLog($stopWatchEvent, $output, $extra);

            $this->writer->write(
                $logs,
                $identifier
            );
        }

        return $logs;
    }

    /**
     * Extract StopWatch data into an array.
     *
     * @param StopwatchEvent $stopWatchEvent
     * @param OutputInterface|null $output
     * @param array $extra
     *
     * @return array
     */
    private function extractLog(StopwatchEvent $stopWatchEvent, OutputInterface $output = null, $extra = array())
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
            'extra' => json_encode($extra)
        );

        if (null !== $output) {
            $output->writeln(sprintf("===   Start time : <info>%s</info>", date('d/m/Y H:i:s', $log['start'])));
            $output->writeln(sprintf("===   End time   : <info>%s</info>", date('d/m/Y H:i:s', $log['end'])));
            $output->writeln(sprintf("===   Duration   : <info>%s seconds</info>", $log['duration']));
            $output->writeln(sprintf("===   Memory     : <info>%s Mb</info>", $log['memory']));
        }

        return $log;
    }

}