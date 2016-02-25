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
        $this->client           = new \MongoClient($dsn);
        $this->db               = $db;
    }

    /**
     * @param $identifier
     *
     * @return array
     */
    public function getLogs($identifier, $category)
    {
        $this->collection = $this->client->selectCollection($this->db, Parser::slugifyIdentifier($identifier));

        $logs = iterator_to_array($this->collection->find());

        return Parser::get($logs, $category);
    }
} 