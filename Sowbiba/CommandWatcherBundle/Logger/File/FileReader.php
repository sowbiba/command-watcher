<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 07/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Logger\File;


use Sowbiba\CommandWatcherBundle\Logger\Parser;
use Sowbiba\CommandWatcherBundle\Logger\ReaderInterface;

class FileReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $logsPath;

    /**
     * @var string
     */
    private $logsPrefix;

    /**
     * @param string $logsPath
     * @param string $logsPrefix
     */
    public function __construct(
        $logsPath,
        $logsPrefix
    )
    {
        $this->logsPath             = rtrim($logsPath, '/') . '/';
        $this->logsPrefix           = $logsPrefix;
    }

    public function getLogs($identifier, $category)
    {
        $identifier = Parser::slugifyIdentifier($identifier);

        $filename = sprintf(
            "%s%s%s.log",
            $this->logsPath,
            $this->logsPrefix,
            $identifier
        );

        if (file_exists($filename)) {
            $logs = json_decode(file_get_contents($filename), true);
        } else {
            $logs = array();
        }

        $logs = isset($logs[$identifier]) ? $logs[$identifier] : array();

        return Parser::get($logs, $category);
    }
} 