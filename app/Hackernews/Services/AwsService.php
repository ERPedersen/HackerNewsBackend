<?php


namespace Hackernews\Services;


use Aws\CloudWatchLogs\CloudWatchLogsClient;

class AwsService
{

    protected static $cloudWatchClient = null;

    /**
     * Returns a client for Cloud Watch logging from
     * a set of environment variables.
     *
     * @return CloudWatchLogsClient
     */
    public static function cloudWatch()
    {
        if (!isset(static::$cloudWatchClient)) {
            static::$cloudWatchClient = new CloudWatchLogsClient([
                'region' => getenv('AWS_REGION'),
                'version' => getenv('AWS_VERSION'),
                'credentials' => [
                    'key' => getenv('AWS_KEY'),
                    'secret' => getenv('AWS_SECRET'),
                ]
            ]);
        }

        return static::$cloudWatchClient;
    }
}