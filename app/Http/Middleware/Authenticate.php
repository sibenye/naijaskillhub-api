<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            throw new AuthenticationException();
        }

        // if user id is in url path,
        // ensure that it is the same as that of the Auth User
        $path = $request->path();
        if ($path) {
            $pathInfo = preg_split('/\//', $path);
            if (count($pathInfo) > 1) {
                $info1 = strtolower($pathInfo [0]);
                $info2 = strtolower($pathInfo [1]);

                if ($info1 == 'users' && is_numeric($info2)) {
                    if ($info2 != Auth::user()->id) {
                        throw new AuthorizationException("id does not match Auth User ID");
                    }
                }
            }
        }

        return $next($request);
    }
}
