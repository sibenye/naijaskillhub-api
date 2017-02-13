<?php
namespace App\Providers;

use App\Models\DAO\User;
use Illuminate\Support\ServiceProvider;
use App\Utilities\NSHJWTClientWrapper;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app ['auth']->viaRequest('api',
                function ($request) {
                    if ($request->header('NSH-AUTH-TOKEN')) {
                        // parse the token
                        $jwtClient = new NSHJWTClientWrapper();
                        $token = $jwtClient->parseToken($request->header('NSH-AUTH-TOKEN'));
                        // check if expired
                        if ($jwtClient->tokenIsExpired($token)) {
                            return null;
                        }
                        // verify token's claim
                        if (!$jwtClient->verifyToken($token)) {
                            return null;
                        }
                        // return user
                        $email = $token->getClaim("email", "");
                        return User::where("emailAddress", $email)->first();
                    } else {
                        return null;
                    }
                });
    }
}
