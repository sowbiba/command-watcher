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
            'commands' => $commands
        ];

        if ('POST' === $request->getMethod()) {
            $command = $request->request->get('command');
            var_dump($command);
            // Chart
            $series = array(
                array("name" => "Data Serie Name", "data" => array(1, 2, 4, 5, 6, 3, 8))
            );

            $ob = new Highchart();
            $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
            $ob->title->text('Chart Title');
            $ob->xAxis->title(array('text' => "Horizontal axis title"));
            $ob->yAxis->title(array('text' => "Vertical axis title"));
            $ob->series($series);

            $parameters['chart'] = $ob;
        }

        return $this->render('SowbibaCommandWatcherBundle:CommandWatcher:index.html.twig', $parameters);
    }
}
