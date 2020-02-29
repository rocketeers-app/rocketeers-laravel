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
        dd('a');
    }
}
