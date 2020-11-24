<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\App;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @throws TokenMismatchException
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Don't validate CSRF when testing.
        if (App::environment(['local', 'testing'])) {
            return $this->addCookieToResponse($request, $next($request));
        }
        // @codeCoverageIgnoreStart
        return parent::handle($request, $next);
        // @codeCoverageIgnoreEnd
    }
}
