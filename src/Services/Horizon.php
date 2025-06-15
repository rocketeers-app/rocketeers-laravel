<?php

namespace Rocketeers\Laravel\Services;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\HeaderBag;

class Horizon
{
    public null|Closure $hasAccess = null;

    public function defineAccess(Closure $closure): void
    {
        $this->hasAccess = $closure;
    }

    public function hasAccess($user = null): bool
    {
        return !is_null($this->hasAccess) ? App::call($this->hasAccess, ['user' => $user]) : false;
    }

    public function getDefaultAccess(): bool
    {
        /**
         * @var \Illuminate\Http\Request $request
         */
        $request = $this->getRequestInstance();

        /**
         * @var HeaderBag $headers
         */
        $headers = $request->headers;

        if (!$headers->has('Authorization')) {
            return false;
        }

        return $request->bearerToken() === Config::get('services.horizon.secret') || $request->bearerToken() === Config::get('rocketeers.api_token');
    }

    public function getRequestInstance(): \Illuminate\Http\Request
    {
        return Request::instance();
    }
}
