<?php


namespace Hackernews\Logging;


use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Exception;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CloudWatchLog extends BaseLog
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
     * Should set up and return a logger from a set of configurations.
     *
     * @return Logger
     */
    protected static function getLogger()
    {

        $cwLogIdentifier = getenv('AWS_CW_LOG_IDENTIFIER');
        $cwLogName = getenv('AWS_CW_LOG_NAME');
        $cwGroup = getenv('AWS_CW_GROUP');
        $cwStreamInstance = getenv('AWS_INSTANCE_ID');
        $cwStreamName = getenv('AWS_CW_STREAM');
        $cwAppName = getenv('AWS_CW_APP_NAME');
        $cwRetentionDays = intval(getenv('AWS_CW_RETENTION'));
        $cwClient = new CloudWatchLogsClient([
            'region' => getenv('AWS_REGION'),
            'version' => getenv('AWS_VERSION'),
            'credentials' => [
                'key' => getenv('AWS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ]
        ]);

        $cwHandlerInstanceNotice = new CloudWatch($cwClient, $cwGroup, $cwStreamInstance, $cwRetentionDays, 10000, ['application' => $cwAppName], Logger::NOTICE);
        $cwHandlerInstanceError = new CloudWatch($cwClient, $cwGroup, $cwStreamInstance, $cwRetentionDays, 10000, ['application' => $cwAppName], Logger::ERROR);
        $cwHandlerAppNotice = new CloudWatch($cwClient, $cwGroup, $cwStreamName, $cwRetentionDays, 10000, ['application' => $cwAppName], Logger::NOTICE);

        $logger = new Logger($cwLogIdentifier);
        $formatter = new LineFormatter("%channel%: %level_name%: %message% %context% %extra%", null, true, true);
        $handler = new StreamHandler(__DIR__ . "/../../../logs/" . $cwLogName, Logger::INFO);
        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);
        $logger->pushHandler($cwHandlerInstanceNotice);
        $logger->pushHandler($cwHandlerInstanceError);
        $logger->pushHandler($cwHandlerAppNotice);

        return $logger;
    }

    /**
     * @param $exception Exception
     * @param $severity
     */
    public function logException($exception, $severity)
    {
        parent::$severity($exception->getMessage(), ["test" => "test"]);
    }
}