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
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestListener
 * @package Sowbiba\CommandWatcherBundle\Listener
 *
 * @link http://symfony.com/doc/current/components/console/events.html
 * @link http://symfony.com/doc/current/components/stopwatch.html
 */
class RequestListener
{
    /**
     * @var Watcher
     */
    private $watcher;

    public function __construct(
        Watcher $watcher
    )
    {
        $this->watcher              = $watcher;
    }

    public function onRequestStart(GetResponseEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        if ('_' === substr($route, 0, 1)) { // symfony system routes
            return;
        }

        $requestUriSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $route);

        $this->watcher->start($requestUriSlug);
    }



    public function onFinishRequest(FinishRequestEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        if ('_' === substr($route, 0, 1)) {
            return;
        }

        $requestUriSlug = preg_replace('/[^a-zA-Z0-9_.]/', '', $route);

        $this->watcher->end($requestUriSlug);
    }
} 