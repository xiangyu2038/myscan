<?php

namespace App\Http\Middleware;
use App\Exceptions\ApiException;
use Illuminate\Auth\AuthenticationException;
use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateApi extends Authenticate
{
    protected function authenticate(array $guards)
    {

        if ($this->auth->guard('api')->check()) {
            return $this->auth->shouldUse('api');
        }

        throw new ApiException('Unauthenticated.', $guards);

    }
}