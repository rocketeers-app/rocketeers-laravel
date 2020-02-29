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
        dd($report);
    }
}
