<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DropshippingXmlFreeVendor\Monolog\Handler;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use DropshippingXmlFreeVendor\MongoDB\Client;
use DropshippingXmlFreeVendor\Monolog\Logger;
use DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface;
use DropshippingXmlFreeVendor\Monolog\Formatter\MongoDBFormatter;
/**
 * Logs to a MongoDB database.
 *
 * Usage example:
 *
 *   $log = new \Monolog\Logger('application');
 *   $client = new \MongoDB\Client('mongodb://localhost:27017');
 *   $mongodb = new \Monolog\Handler\MongoDBHandler($client, 'logs', 'prod');
 *   $log->pushHandler($mongodb);
 *
 * The above examples uses the MongoDB PHP library's client class; however, the
 * MongoDB\Driver\Manager class from ext-mongodb is also supported.
 */
class MongoDBHandler extends \DropshippingXmlFreeVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var \MongoDB\Collection */
    private $collection;
    /** @var Client|Manager */
    private $manager;
    /** @var string */
    private $namespace;
    /**
     * Constructor.
     *
     * @param Client|Manager $mongodb    MongoDB library or driver client
     * @param string         $database   Database name
     * @param string         $collection Collection name
     */
    public function __construct($mongodb, string $database, string $collection, $level = \DropshippingXmlFreeVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!($mongodb instanceof \DropshippingXmlFreeVendor\MongoDB\Client || $mongodb instanceof \MongoDB\Driver\Manager)) {
            throw new \InvalidArgumentException('MongoDB\\Client or MongoDB\\Driver\\Manager instance required');
        }
        if ($mongodb instanceof \DropshippingXmlFreeVendor\MongoDB\Client) {
            $this->collection = $mongodb->selectCollection($database, $collection);
        } else {
            $this->manager = $mongodb;
            $this->namespace = $database . '.' . $collection;
        }
        parent::__construct($level, $bubble);
    }
    protected function write(array $record) : void
    {
        if (isset($this->collection)) {
            $this->collection->insertOne($record['formatted']);
        }
        if (isset($this->manager, $this->namespace)) {
            $bulk = new \MongoDB\Driver\BulkWrite();
            $bulk->insert($record["formatted"]);
            $this->manager->executeBulkWrite($this->namespace, $bulk);
        }
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter() : \DropshippingXmlFreeVendor\Monolog\Formatter\FormatterInterface
    {
        return new \DropshippingXmlFreeVendor\Monolog\Formatter\MongoDBFormatter();
    }
}
