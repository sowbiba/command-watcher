<?php

namespace Sowbiba\CommandWatcherBundle\Controller;

use Sowbiba\CommandWatcherBundle\Chart\Chart;
use Sowbiba\CommandWatcherBundle\Logger\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandWatcherController extends Controller
{
    public function indexAction(Request $request)
    {
        $logReader = $this->get('sowbiba_command_watcher.log_reader');

        $parameters = array(
            'commands' => $logReader->getCommands(),
            'categories' => Parser::$categories,
        );

        if ($request->query->has('command')) {
            $command = $request->query->get('command');
            $category = $request->query->get('category');

            if (!empty($command)) {
                $logs = $logReader->getLogs($command, $category);

                $parameters['chart'] = Chart::get($command, $category, $logs);
                $parameters['selected_command'] = $command;
                $parameters['selected_category'] = $category;
            }
        }

        return $this->render('SowbibaCommandWatcherBundle:CommandWatcher:index.html.twig', $parameters);
    }
}
