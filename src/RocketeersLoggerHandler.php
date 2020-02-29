<?php

namespace Rocketeers\Laravel;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Rocketeers\Rocketeers;

class RocketeersLoggerHandler extends AbstractProcessingHandler
{
    protected $client;

    public function __construct(Rocketeers $client, $level = Logger::DEBUG, $bubble = true)
    {
        $this->client = $client;

        parent::__construct($level, $bubble);
    }

    protected function write(array $report): void
    {
        $this->client->report([
            'message' => $report['message'],
            'context' => $report['context'],
            'level' => $report['level'],
            'level_name' => $report['level_name'],
            'channel' => $report['channel'],
            'datetime' => $report['datetime'],
            'extra' => $report['extra'],
        ]);

        return;
    }
}
