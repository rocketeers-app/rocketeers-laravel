<?php

namespace Rocketeers\Laravel;

use Illuminate\Http\Request;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Rocketeers\Rocketeers;

class RocketeersLoggerHandler extends AbstractProcessingHandler
{
    protected $client;
    protected $request;

    public function __construct(Rocketeers $client, $level = Logger::DEBUG, $bubble = true)
    {
        $this->client = $client;
        $this->request = Request::createFromGlobals();

        parent::__construct($level, $bubble);
    }

    protected function write(array $report): void
    {
        $this->client->report([
            'message' => $report['message'],
            'context' => $report['context'],
            'exception' => $report['context']['exception'],
            'level' => $report['level'],
            'level_name' => $report['level_name'],
            'channel' => $report['channel'],
            'datetime' => $report['datetime'],
            'extra' => $report['extra'],
            'url' => $this->request->getUri(),
            'ip_address' => $this->request->getClientIp(),
            'method' => $this->request->getMethod(),
            'user_agent' => $this->request->headers->get('User-Agent'),
        ]);

        return;
    }
}
