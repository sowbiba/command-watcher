<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:31
 */

namespace Sowbiba\CommandWatcherBundle\Writer;


use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\Filesystem\Exception\IOException;

class FileWriter extends AbstractWriter
{
    private $logsPath;

    private $logsPrefix;

    public function __construct($logsPath, $logsPrefix)
    {
        $this->logsPath     = rtrim($logsPath, '/') . '/';
        $this->logsPrefix   = $logsPrefix;
    }

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
            "%s%s%s.log",
            $this->logsPath,
            $this->logsPrefix,
            $identifier
        );

        $logContent = sprintf(
            "%s;%s;%s;%s;%s\n",
            $log['start'],
            $log['end'],
            $log['duration'],
            $log['memory'],
            $log['arguments']
        );

        if (! file_put_contents($filename, $logContent, FILE_APPEND | LOCK_EX)) {
            throw new IOException("Cannot write to $filename");
        }
    }
}