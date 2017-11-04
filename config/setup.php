<?php

use Aws\CloudWatchLogs\CloudWatchLogsClient;

define('CW_GROUP_USERS', 'users');
define('CW_GROUP_API', 'api');
define('CW_GROUP_DEBUG', 'debug');
define('CW_GROUP_EXCEPTIONS', 'exceptions');
define('CW_RETENTION_DAYS', 100);
define('CW_BATCH_SIZE', 10000);
define('CW_APP_NAME', 'hackernews-backend');