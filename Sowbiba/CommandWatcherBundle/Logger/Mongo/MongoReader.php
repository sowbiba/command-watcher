<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 07/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Logger\Mongo;


use Sowbiba\CommandWatcherBundle\Logger\Parser;
use Sowbiba\CommandWatcherBundle\Logger\ReaderInterface;

class MongoReader implements ReaderInterface
{
    /**
     * @var array
     */
    private $listenedCommands;

    /**
     * The Mongo client to request DB.
     *
     * @var \MongoClient
     */
    private $client;

    /**
     * The database to use.
     *
     * @var string
     */
    private $db;

    /**
     * The collection to work on.
     *
     * @var \MongoCollection
     */
    private $collection;

    /**
     * @param string $dsn The DSN to connect to MongoDB.
     * @param string $db The collection to use.
     */
    public function __construct($dsn, $db, array $listenedCommands)
    {
        $this->client           = new \MongoClient($dsn);
        $this->db               = $db;
        $this->listenedCommands = $listenedCommands;
    }

    /**
     * @param $command
     *
     * @return array
     */
    public function getLogs($command)
    {
        if (!in_array($command, $this->listenedCommands)) {
            throw new \InvalidArgumentException("Command is not listened");
        }

        $this->collection = $this->client->selectCollection($this->db, Parser::slugifyCommand($command));

        $logs = $this->collection->find();

        return iterator_to_array($logs);
    }

    /**
     * @param $command
     *
     * @return array
     */
    public function getDurationLogs($command)
    {
        return Parser::get($this->getLogs($command), 'duration');
    }

    /**
     * @param $command
     *
     * @return array
     */
    public function getMemoryLogs($command)
    {
        return Parser::get($this->getLogs($command), 'memory');
    }
} 