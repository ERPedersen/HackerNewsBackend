<?php


namespace Hackernews\Logging;


interface IBaseLog
{
    /**
     * Logs a message with the debug level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function debug($msg, $data);

    /**
     * Logs a message with the info level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function info($msg, $data);

    /**
     * Logs a message with the notice level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function notice($msg, $data);

    /**
     * Logs a message with the warning level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function warning($msg, $data);

    /**
     * Logs a message with the error level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function error($msg, $data);

    /**
     * Logs a message with the critical level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function critical($msg, $data);

    /**
     * Logs a message with the alert level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function alert($msg, $data);

    /**
     * Logs a message with the emergency level.
     *
     * @param $msg string Message to log
     * @param $data
     * @return void
     */
    public function emergency($msg, $data);
}