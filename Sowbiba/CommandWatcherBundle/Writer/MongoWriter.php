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

class MongoWriter extends AbstractWriter
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
    public function __construct($dsn, $db)
    {
        $this->client = new \MongoClient($dsn);
        $this->db = $db;
    }

    public function write(array $log, $identifier)
    {
        $this->client->selectCollection($this->db, $identifier);

        $this->collection->insert([
            'start' => $log['start'],
            'end' => $log['end'],
            'duration' => $log['duration'],
            'memory' => $log['memory'],
            'arguments' => $log['arguments']
        ]);
    }
}