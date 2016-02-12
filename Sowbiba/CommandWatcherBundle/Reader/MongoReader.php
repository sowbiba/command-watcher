<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 07/02/16
 * Time: 13:27
 */
namespace Sowbiba\CommandWatcherBundle\Reader;


class MongoReader extends AbstractReader
{
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
        $this->client = new \MongoClient($dsn);
        $this->db = $db;

        parent::__construct($listenedCommands);
    }

    public function getLogs($identifier)
    {
        $this->collection = $this->client->selectCollection($this->db, $identifier);

        $logs = $this->collection->find();

        return iterator_to_array($logs);
    }
} 