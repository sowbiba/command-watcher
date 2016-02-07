<?php

namespace Sowbiba\CommandWatcherBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandWatcherController extends Controller
{
    public function indexAction(Request $request)
    {
        $logReader = $this->get('sowbiba_command_watcher.log_reader');

        $commands = $logReader->getCommands();

        $parameters = [
            'commands' => $commands,
            'categories' => ['duration', 'memory']
        ];

        if ('POST' === $request->getMethod()) {
            $command = $request->request->get('command');
            $category = $request->request->get('category');

            if (!empty($command)) {
                $logs = [];
                $unit = "";

                if ('duration' === $category) {
                    $logs = $logReader->getDurationLogs($command);
                    $unit = "seconds";
                }
                if ('memory' === $category) {
                    $logs = $logReader->getMemoryLogs($command);
                    $unit = "Mb";
                }

                // Chart
                $series = array(
                    array("name" => sprintf("%s - %s statistics", $command, ucfirst($category)), "data" => array_values($logs)),
                );

                $ob = new Highchart();
                $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
                $ob->title->text('Chart Title');
                $ob->xAxis->title(array('text' => "Date and time"));
                $ob->xAxis->categories(array_keys($logs));
                $ob->yAxis->title(array('text' => sprintf("%s (in %s)", ucfirst($category), $unit)));
                $ob->series($series);

                $parameters['chart'] = $ob;
                $parameters['selected_command'] = $command;
                $parameters['selected_category'] = $category;
            }
        }

        return $this->render('SowbibaCommandWatcherBundle:CommandWatcher:index.html.twig', $parameters);
    }
}
