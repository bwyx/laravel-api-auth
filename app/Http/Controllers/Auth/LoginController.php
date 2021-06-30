<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider) {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider) {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $userProvider = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $user = User::firstOrCreate(
            [
                'email' => $userProvider->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $userProvider->getName(),
            ]
        );

        $user->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $userProvider->getId(),
            ],
            [
                'avatar' => $userProvider->getAvatar()
            ]
        );

        Auth::login($user);
        return redirect()->away(env('FRONTEND_URL'));
    }

    protected function validateProvider($provider) {
        if(!in_array($provider, ['google'])) {
            return redirect()->away(env('FRONTEND_URL'));
        }
    }
}
