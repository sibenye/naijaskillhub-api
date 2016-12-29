<?php
namespace App\Http\Middleware;

use App\Enums\AccountType;
use App\Repositories\UserRepository;
use Closure;
use App\Models\Responses\NSHResponse;

class AccountTypeCheck
{
    /**
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new middleware instance.
     *
     * @param  UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userId = $request->segment(2, NULL);
        if (empty($userId) || !$this->hasTalentAccountType($userId)) {
            $nsh_response = new NSHResponse();
            $nsh_response->setMessage("User must have accountType of 'talent'");
            $nsh_response->setHttpStatus(401);
            $nsh_response->setStatus('error');
            return $nsh_response->render();
        }

        return $next($request);
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
