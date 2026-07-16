<?php

function writeLog($action, $message)
{
    $logFile = __DIR__ . "/../logs/app.log";

    $date = date("Y-m-d H:i:s");

    $log = "[{$date}] {$action} - {$message}" . PHP_EOL;

    file_put_contents($logFile, $log, FILE_APPEND);
}
