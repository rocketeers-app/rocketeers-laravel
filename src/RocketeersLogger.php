<?php

namespace Rocketeers\Laravel;

class RocketeersLogger
{
    protected $rocketeers;

    public function __construct($rocketeers)
    {
        $this->rocketeers = $rocketeers;
    }

    public function log($message, $level)
    {
        return app('rocketeers-logger')
            ->setMessage($message)
            ->setLevel($level);
    }

    public function send()
    {
        dd($this);
    }
}
