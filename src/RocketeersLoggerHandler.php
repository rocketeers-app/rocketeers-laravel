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
        if (! is_null($report['context']['exception'])) {
            $this->client->report([
                'channel' => $report['channel'],
                'environment' => app()->environment(),
                'code' => $this->getCodeFromException($report['context']['exception']),
                'context' => $report['context'],
                'datetime' => $report['datetime'],
                'exception' => method_exists($report['context']['exception'], 'getOriginalClassName') ? $report['context']['exception']->getOriginalClassName() : get_class($report['context']['exception']),
                'extra' => $report['extra'] ?: null,
                'file' => $report['context']['exception']->getFile(),
                'level_name' => $report['level_name'],
                'level' => $report['level'],
                'line' => $report['context']['exception']->getLine(),
                'message' => $report['context']['exception']->getMessage(),
                'trace' => $report['context']['exception']->getTrace(),
                'method' => ! app()->runningInConsole() ? $this->request->getMethod() : null,
                'url' => app()->runningInConsole() ? config('app.url') : $this->request->getUri(),
                'command' => trim(implode(' ', $this->request->server('argv', null) ?: [])),
                'referrer' => $this->request->server('HTTP_REFERER'),
                'querystring' => $this->request->query->all() ?: null,
                'user_name' => $this->request->user() ? $this->request->user()->name : null,
                'ip_address' => $this->request->getClientIp(),
                'hostname' => $this->request->getClientIp() && $this->request->getClientIp() !== '127.0.0.1' ? gethostbyaddr($this->request->getClientIp()) : 'localhost',
                'user_agent' => $this->request->headers->get('User-Agent'),
                'inputs' => $this->request->all() ?: null,
                'files' => $this->getFiles(),
                'headers' => $this->request->headers->all(),
                'sessions' => $this->request->getSession() ? $this->request->session->all() : null,
                'cookies' => $this->request->cookies->all(),
            ]);
        }
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
