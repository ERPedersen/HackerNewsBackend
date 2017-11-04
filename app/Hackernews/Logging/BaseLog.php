<?php


namespace Hackernews\Logging;


use Monolog\Logger;

abstract class BaseLog implements IBaseLog
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * BaseLog constructor.
     * @param Logger $logger
     * @internal param string $logName
     * @internal param string $logPath
     * @internal param LineFormatter $formatter
     * @internal param StreamHandler $handler
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Should set up and return a logger from a set of configurations.
     *
     * @return Logger
     */
    protected abstract static function getLogger();

    /**
     * Logs a message with the debug level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function debug($msg, $data = [])
    {
        $this->logger->debug($msg, $data);
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