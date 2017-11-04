<?php


namespace Hackernews\Logging;


use Exception;
use Hackernews\Services\AwsService;
use Maxbanton\Cwh\Handler\CloudWatch;
use Slim\Http\Request;

class ApiLogger extends BaseLogger
{
    protected static $instance = null;

    /**
     * Call this method to get singleton
     *
     * @return ApiLogger
     */
    public static function Instance()
    {
        if (static::$instance === null) {
            static::$instance = new ApiLogger();
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
        $handlers[] = new CloudWatch(AwsService::cloudWatch(), CW_GROUP_API, CW_APP_NAME, CW_RETENTION_DAYS);
        $handlers[] = new CloudWatch(AwsService::cloudWatch(), CW_GROUP_API, getenv('AWS_INSTANCE_ID'), CW_RETENTION_DAYS);
        return $handlers;
    }

    /**
     * @param string $severity
     * @param Request $request
     * @param array $extra
     * @internal param $exception
     */
    public function logEndpointEvent(string $severity, Request $request, array $extra = [])
    {
        $context = [
            "endpoint" => $request->getRequestTarget(),
            "address" => $request->getAttribute('ip_address'),
            "user" => $request->getAttribute("user_id"),
            "alias" => $request->getAttribute("user_alias"),
        ];

        parent::$severity($request->getRequestTarget(), array_merge($context, $extra));
    }
}