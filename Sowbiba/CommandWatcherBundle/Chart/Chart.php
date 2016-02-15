<?php

/**
 * Created by PhpStorm.
 * User: isow
 * Date: 15/02/16
 * Time: 12:34
 */
namespace Sowbiba\CommandWatcherBundle\Chart;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Sowbiba\CommandWatcherBundle\Logger\Parser;

abstract class Chart implements ChartInterface
{
    /**
     * @param string $command
     * @param string $category
     * @param array $logs
     *
     * @return Highchart
     */
    public static function get($command, $category, array $logs)
    {
        $series = array(
            array("name" => sprintf("%s - %s statistics", $command, ucfirst($category)), "data" => array_values($logs)),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text' => "Date and time"));
        $ob->xAxis->categories(array_keys($logs));
        $ob->yAxis->title(array('text' => sprintf("%s (in %s)", ucfirst($category), Parser::getCategoryUnit($category))));
        $ob->series($series);

        return $ob;
    }
}