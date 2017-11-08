<?php


namespace Hackernews\Logging;


use Exception;
use Hackernews\Services\AwsService;
use Maxbanton\Cwh\Handler\CloudWatch;

class DebugLogger extends BaseLogger
{
    protected static $instance = null;

    /**
     * Call this method to get singleton
     *
     * @return DebugLogger
     */
    public static function Instance()
    {
        if (static::$instance === null) {
            static::$instance = new DebugLogger();
        }
        return static::$instance;
    }

    /**
     * Sets up a list of handlers for the logger.
     *
     * @return Cloudwatch[]
     */
    protected static function getHandlers()
    {
        $handlers = [];
        $handlers[] = new CloudWatch(AwsService::cloudWatch(), CW_GROUP_DEBUG, CW_APP_NAME, CW_RETENTION_DAYS);
        $handlers[] = new CloudWatch(AwsService::cloudWatch(), CW_GROUP_DEBUG, getenv('AWS_INSTANCE_ID'), CW_RETENTION_DAYS);
        return $handlers;
    }
}