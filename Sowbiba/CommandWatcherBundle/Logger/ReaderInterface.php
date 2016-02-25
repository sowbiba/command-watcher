<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 11/02/16
 * Time: 11:22
 */
namespace Sowbiba\CommandWatcherBundle\Logger;


interface ReaderInterface
{
    /**
     * @param string $command
     * @param string $category
     *
     * @return array
     */
    public function getLogs($identifier, $category);
}