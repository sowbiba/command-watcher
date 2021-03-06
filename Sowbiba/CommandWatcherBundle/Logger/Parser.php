<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 12/02/16
 * Time: 13:59
 */

namespace Sowbiba\CommandWatcherBundle\Logger;


abstract class Parser
{
    const TYPE_DATE = 1;

    public static $categories = array(
        'duration',
        'memory'
    );

    /**
     * @param array $logs
     * @param $category
     *
     * @return array
     */
    public static function get(array $logs, $category)
    {
        if (!in_array($category, self::$categories)) {
            throw new \InvalidArgumentException(" Category [ $category ] is not available.");
        }

        $categoryLogs = array();
        foreach ($logs as $log) {
            $categoryLogs[self::convert($log['start'], self::TYPE_DATE)] = floatval($log[$category]);
        }

        return $categoryLogs;
    }

    /**
     * @param $value
     * @param $type
     *
     * @return bool|string
     */
    private static function convert($value, $type)
    {
        switch ($type) {
            case self::TYPE_DATE:
                return date("d/m/Y H:i:s", $value);
            default:
                return $value;
        }
    }

    /**
     * @param string $identifier
     *
     * @return mixed
     */
    public static function slugifyIdentifier($identifier)
    {
        return preg_replace('/[^a-zA-Z0-9_.]/', '', $identifier);
    }

    /**
     * @param string $category
     *
     * @return string
     */
    public static function getCategoryUnit($category)
    {
        $unit = "";
        if ('duration' === $category) {
            $unit = "seconds";
        }
        if ('memory' === $category) {
            $unit = "Mb";
        }

        return $unit;
    }
}