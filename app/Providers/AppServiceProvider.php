<?php
namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider {
    public function register(): void { }
    public function boot(): void {
        Gate::define('moderate-deals', fn (User $user) => $user->isStaff());
        Gate::define('manage-users', fn (User $user) => $user->isAdmin());
    }
}
