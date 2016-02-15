<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:31
 */

namespace Sowbiba\CommandWatcherBundle\Logger\File;


use Sowbiba\CommandWatcherBundle\Logger\WriterInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\Filesystem\Exception\IOException;

class FileWriter implements WriterInterface
{
    private $logsPath;

    private $logsPrefix;

    /**
     * @param string $logsPath
     * @param string $logsPrefix
     */
    public function __construct($logsPath, $logsPrefix)
    {
        $this->logsPath     = rtrim($logsPath, '/') . '/';
        $this->logsPrefix   = $logsPrefix;
    }

    /**
     * @param array $log
     * @param $identifier
     *
     * @throws ContextErrorException
     *
     * @return bool
     */
    public function write(array $log, $identifier)
    {
        if (!is_dir($this->logsPath)) {
            throw new ContextErrorException(
                sprintf("Directory [ %s ] does not exist", $this->logsPath), 0, E_USER_ERROR, $this->logsPath, __LINE__
            );
        }

        if (!is_writable($this->logsPath)) {
            throw new ContextErrorException(
                sprintf("Directory [ %s ] is not writable", $this->logsPath), 0, E_USER_ERROR, $this->logsPath, __LINE__
            );
        }

        $filename = sprintf(
            "%s%scommand-watcher.log",
            $this->logsPath,
            $this->logsPrefix
        );

        if (file_exists($filename)) {
            $logs = json_decode(file_get_contents($filename), true);
        } else {
            $logs = array();
        }

        if (isset($logs[$identifier])) {
            array_push($logs[$identifier], $log);
        } else {
            $logs[$identifier] = array();
            array_push($logs[$identifier], $log);
        }

        if (! file_put_contents($filename, json_encode($logs))) {
            throw new IOException("Cannot write to $filename");
        }

        return true;
    }
}