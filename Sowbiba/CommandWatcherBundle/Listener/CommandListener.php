<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 06/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Listener;

use Sowbiba\CommandWatcherBundle\Watcher\Watcher;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * Class CommandListener
 * @package Sowbiba\CommandWatcherBundle\Listener
 *
 * @link http://symfony.com/doc/current/components/console/events.html
 * @link http://symfony.com/doc/current/components/stopwatch.html
 */
class CommandListener
{
    /**
     * @var Watcher
     */
    private $watcher;

    /**
     * @var array
     */
    private $listenedCommands;

    public function __construct(
        Watcher $watcher,
        array $listenedCommands
    )
    {
        $this->listenedCommands     = $listenedCommands;
        $this->watcher              = $watcher;
    }

    public function onCommandStart(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        if (!in_array($command->getName(), $this->listenedCommands)) {
            return;
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command->getName());

        $this->watcher->start($commandSlug);
    }



    public function onCommandEnd(ConsoleTerminateEvent $event)
    {
        $command = $event->getCommand();

        if (!in_array($command->getName(), $this->listenedCommands)) {
            return;
        }

        $commandSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $command->getName());

        $this->watcher->end($commandSlug, $event->getOutput(), $event->getInput()->getArguments());
    }
} 