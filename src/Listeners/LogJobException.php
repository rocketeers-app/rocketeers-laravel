<?php

namespace Rocketeers\Laravel\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\InteractsWithQueue;
use Rocketeers\Rocketeers;

class LogJobException
{
    protected $client;

    public function __construct(Rocketeers $client)
    {
        $this->client = $client;
    }

    public function handle(JobExceptionOccurred $event)
    {
        $this->client->report([
            'environment' => app()->environment(),
            'code' => $this->getCodeFromException($event->exception),
            'exception' => method_exists($event->exception, 'getOriginalClassName') ? $event->exception->getOriginalClassName() : get_class($event->exception),
            'message' => $event->exception->getMessage(),
            'file' => $event->exception->getFile(),
            'line' => $event->exception->getLine(),
            'trace' => $event->exception->getTrace(),
            'url' => config('app.url'),
        ]);
    }

    protected function getCodeFromException($exception)
    {
        $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();

        return $code == 0 ? 500 : $code;
    }
}
