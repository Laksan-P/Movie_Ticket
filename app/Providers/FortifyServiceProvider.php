<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\RecoveryCodesGeneratedResponse::class,
            \App\Http\Responses\RecoveryCodesGeneratedResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorConfirmedResponse::class,
            \App\Http\Responses\TwoFactorConfirmedResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView('auth.login');
        Fortify::registerView('auth.register');
        Fortify::twoFactorChallengeView('auth.two-factor-challenge');
        Fortify::confirmPasswordView('auth.confirm-password');

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Prevent brute force login attempts using Laravel rate limiting (5 per minute per email/IP)
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Prevent brute force two-factor authentication attempts using Laravel rate limiting
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            // Prevent SQL Injection using Laravel Eloquent ORM (parameterized queries)
            $user = \App\Models\User::where('email', $request->email)->first();

            // Secure password verification using Laravel Hash facade (bcrypt)
            if ($user &&
                $user->email === $request->email &&
                \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }
}
