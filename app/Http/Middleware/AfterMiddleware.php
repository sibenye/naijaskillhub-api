<?php
namespace App\Http\Middleware;

use App\Enums\AccountType;
use App\Repositories\UserRepository;
use Closure;

class AfterMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->withHeaders(
                [
                        "Access-Control-Allow-Origin" => env("ALLOW_ORIGIN"),
                        "Access-Control-Allow-Methods" => "GET,POST,PUT,DELETE,OPTIONS",
                        "Access-Control-Allow-Headers" => "NSH-API-KEY,NSH-AUTH-TOKEN,Content-Type,Location"
                ]);
        return $response;
    }

    private function hasTalentAccountType($userId)
    {
        $user = $this->userRepository->get($userId);

        $hasTalentAccount = false;

        foreach ($user->accountTypes as $accountType) {
            if ($accountType->name == AccountType::TALENT) {
                $hasTalentAccount = true;
            }
        }
        return $hasTalentAccount;
    }
}
