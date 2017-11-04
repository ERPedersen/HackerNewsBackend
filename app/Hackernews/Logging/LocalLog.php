<?php


namespace Hackernews\Logging;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LocalLog extends BaseLog
{
    /**
     * Call this method to get singleton
     *
     * @return LocalLog
     */
    public static function Instance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new LocalLog(self::getLogger());
        }
        return $instance;
    }

    /**
     * Returns a logger form a set of configurations
     *
     * @return Logger
     */
    protected static function getLogger()
    {
        $logger = new Logger("HackerNews");
        $formatter = new LineFormatter(null, null, false, true);
        $handler = new StreamHandler(__DIR__ . "/../../../logs/hackernews.log", Logger::INFO);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    }
}