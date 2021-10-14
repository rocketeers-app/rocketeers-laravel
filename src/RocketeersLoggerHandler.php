<?php

namespace Rocketeers\Laravel;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        if (! in_array(app()->environment(), config('rocketeers.environments'))) {
            return;
        }

        if (!isset($report['context']['exception'])) {
            return;
        }

        $this->client->report([
            'channel' => $report['channel'], // not saved currently
            'environment' => app()->environment(),
            'code' => $this->getCodeFromException($report['context']['exception']),
            'exception' => method_exists($report['context']['exception'], 'getOriginalClassName') ? $report['context']['exception']->getOriginalClassName() : get_class($report['context']['exception']),
            'message' => $report['context']['exception']->getMessage(),
            'context' => $report['context'], // not saved currently
            'datetime' => $report['datetime'], // not saved currently
            'extra' => $report['extra'] ?: null, // not saved currently
            'level' => $report['level'], // not saved currently
            'level_name' => $report['level_name'], // not saved currently
            'file' => $report['context']['exception']->getFile(),
            'line' => $report['context']['exception']->getLine(),
            'trace' => $report['context']['exception']->getTrace(),
            'method' => app()->runningInConsole() ? null : $this->request->getMethod(),
            'url' => app()->runningInConsole() ? config('app.url') : $this->request->getUri(),
            'querystring' => $this->request->query->all() ?: null,
            'referrer' => $this->request->server('HTTP_REFERER'),
            'headers' => $this->request->headers->all(),
            'cookies' => $this->request->cookies->all(),
            'files' => $this->getFiles(),
            'inputs' => $this->request->all() ?: null,
            'sessions' => $this->request->getSession() ? $this->request->session()->all() : null,
            'user_name' => $this->request->user() ? $this->request->user()->name : null,
            'user_agent' => $this->request->headers->get('User-Agent'),
            'ip_address' => $this->request->getClientIp(),
            'hostname' => $this->getHostname(),
            'command' => trim(implode(' ', $this->request->server('argv', null) ?: [])),
        ]);
    }

    public function getHostname()
    {
        if($this->request->getClientIp() == '127.0.0.1') {
            return shell_exec('hostname');
        }

        return gethostbyaddr($this->request->getClientIp());
    }

    protected function getCodeFromException($exception)
    {
        $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();

        return $code == 0 ? 500 : $code;
    }

    protected function getFiles(): array
    {
        if (is_null($this->request->files)) {
            return [];
        }

        return $this->mapFiles($this->request->files->all());
    }

    protected function mapFiles(array $files)
    {
        return array_map(function ($file) {
            if (is_array($file)) {
                return $this->mapFiles($file);
            }

            if (! $file instanceof UploadedFile) {
                return;
            }

            try {
                $fileSize = $file->getSize();
            } catch (\RuntimeException $e) {
                $fileSize = 0;
            }

            try {
                $mimeType = $file->getMimeType();
            } catch (\Exception $e) {
                $mimeType = 'undefined';
            }

            return [
                'pathname' => $file->getPathname(),
                'size' => $fileSize,
                'mimeType' => $mimeType,
            ];
        }, $files);
    }
}
