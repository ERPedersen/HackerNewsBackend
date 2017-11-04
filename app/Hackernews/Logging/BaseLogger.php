<?php


namespace Hackernews\Logging;


use Maxbanton\Cwh\Handler\CloudWatch;
use Mockery\Exception;
use Monolog\Logger;

abstract class BaseLogger implements IBaseLogger
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * BaseLogger constructor.
     */
    public function __construct()
    {
        $logger = new Logger(getenv('AWS_CW_LOG_IDENTIFIER'));

        foreach (static::getHandlers() as $handler) {
            $logger->pushHandler($handler);
        }

        $this->logger = $logger;
    }

    /**
     * Should set up and return a logger from a set of configurations.
     *
     * @return CloudWatch[]
     */
    protected abstract static function getHandlers();

    /**
     * Logs a message with the debug level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function debug($msg, $data = [])
    {
        try {
            $this->logger->debug($msg, $data);
        } catch (Exception $e) {
            die('test');
        }
    }

    /**
     * Logs a message with the info level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function info($msg, $data = [])
    {
        $this->logger->info($msg, $data);
    }

    /**
     * Logs a message with the notice level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function notice($msg, $data = [])
    {
        $this->logger->notice($msg, $data);
    }

    /**
     * Logs a message with the warning level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function warning($msg, $data = [])
    {
        $this->logger->warning($msg, $data);
    }

    /**
     * Logs a message with the error level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function error($msg, $data = [])
    {
        $this->logger->error($msg, $data);
    }

    /**
     * Logs a message with the critical level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function critical($msg, $data = [])
    {
        $this->logger->critical($msg, $data);
    }

    /**
     * Logs a message with the alert level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function alert($msg, $data = [])
    {
        $this->logger->alert($msg, $data);
    }

    /**
     * Logs a message with the emergency level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function emergency($msg, $data = [])
    {
        $this->logger->emergency($msg, $data);
    }
}