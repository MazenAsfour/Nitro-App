<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use app\Http\Controller\UserController;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
         // Add the softDeletes route macro here
        Route::macro('softDeletes', function ($uri, $controller) {
            Route::prefix($uri)->group(function () use ($controller) {
                Route::get('/trashed', $controller.'@trashed')->name('users.trashed');
                Route::patch('/{user}/restore', $controller.'@restore')->name('users.restore');
                Route::delete('/{user}/delete', $controller.'@delete')->name('users.delete');
            });
        });
    }
}
