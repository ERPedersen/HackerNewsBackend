<?php


namespace Hackernews\Logging;


use Exception;
use Hackernews\Services\AwsService;
use Maxbanton\Cwh\Handler\CloudWatch;
use Slim\Http\Request;

class ExceptionLogger extends BaseLogger
{
    protected static $instance = null;

    /**
     * Call this method to get singleton
     *
     * @return ExceptionLogger
     */
    public static function Instance()
    {
        if (static::$instance === null) {
            static::$instance = new ExceptionLogger();
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
        $handlers[] = new CloudWatch(AwsService::cloudWatch(), CW_GROUP_EXCEPTIONS, CW_APP_NAME, CW_RETENTION_DAYS);
        $handlers[] = new CloudWatch(AwsService::cloudWatch(), CW_GROUP_EXCEPTIONS, getenv('AWS_INSTANCE_ID'), CW_RETENTION_DAYS);
        return $handlers;
    }

    /**
     * @param $exception Exception
     * @param string $severity
     * @param array $extra
     */
    public function logException(Exception $exception, string $severity, array $extra = [])
    {
        $context = [
            "message" => $exception->getMessage(),
            "file" => $exception->getFile() . " (line: " . $exception->getLine() . ")",
            "code" => $exception->getCode()
        ];

        parent::$severity($exception->getMessage(), array_merge($context, $extra));
    }

    /**
     * @param $exception
     * @param $severity
     * @param Request $request
     * @param $extra
     */
    public function logEndpointException(Exception $exception, string $severity, Request $request, array $extra = [])
    {
        $context = [
            "message" => $exception->getMessage(),
            "file" => $exception->getFile() . " (line: " . $exception->getLine() . ")",
            "code" => $exception->getCode(),
            "endpoint" => $request->getRequestTarget(),
            "address" => $request->getAttribute('ip_address'),
            "user" => $request->getAttribute("user_id"),
            "alias" => $request->getAttribute("user_alias"),
        ];

        parent::$severity($exception->getMessage(), array_merge($context, $extra));
    }
}