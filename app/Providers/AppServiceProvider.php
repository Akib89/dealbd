<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('moderate-deals', fn (User $user) => $user->isStaff());
        Gate::define('manage-users', fn (User $user) => $user->isAdmin());

        RateLimiter::for('login', function (Request $request): array {
            $email = Str::lower(trim((string) $request->input('email')));

            return [
                Limit::perMinute(20)->by('login-ip:'.$request->ip()),
                Limit::perMinute(5)->by('login-account:'.$email.'|'.$request->ip()),
            ];
        });
        RateLimiter::for('registration', fn (Request $request) => Limit::perHour(5)->by($request->ip()));
        RateLimiter::for('deal-submission', fn (Request $request) => Limit::perHour(10)->by((string) $request->user()->id));
        RateLimiter::for('community-write', fn (Request $request) => Limit::perMinute(30)->by((string) $request->user()->id));
        RateLimiter::for('reporting', fn (Request $request) => Limit::perHour(12)->by((string) $request->user()->id));
        RateLimiter::for('admin-write', fn (Request $request) => Limit::perMinute(60)->by((string) $request->user()->id));
    }
}
