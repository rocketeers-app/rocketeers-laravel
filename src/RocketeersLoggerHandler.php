<?php

namespace Rocketeers\Laravel;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Rocketeers\Laravel\Facades\RocketeersLogger;

class PusherLoggerHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $level = strtolower(Logger::getLevelName($record['level']));

        RocketeersLogger::log($record['message'], $level)
            ->send();
    }
}
