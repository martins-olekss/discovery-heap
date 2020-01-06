<?php
require_once __DIR__ . '/../bootstrap.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class Queue
 */
class Queue {
    public $connection;
    public $channel;
    public $callback;
    public $queueName = 'queue';

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection($_ENV['QUEUE_HOST'], $_ENV['QUEUE_PORT'], $_ENV['QUEUE_USER'], $_ENV['QUEUE_PASSWORD']);
        $this->channel = $this->connection->channel();
        $this->createQueue($this->queueName);
        $this->setCallback(function ($msg) {
            echo ' [x] Received ', $msg->body, PHP_EOL;
        });
    }

    /**
     * @param $name
     */
    public function createQueue($name) {
        $this->channel->queue_declare($name, false, false, false, false);
    }

    /**
     * @param $fn
     */
    public function setCallback($fn) {
        $this->callback = $fn;
    }

    public function consume() {
        $this->channel->basic_consume($this->queueName, '', false, true, false, false, $this->callback);
        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait();
            } catch (ErrorException $e) {
            }
        }
    }
}