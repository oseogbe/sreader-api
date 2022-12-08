<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api/admin')
                ->group(base_path('routes/api-admin.php'));

            Route::middleware('api')
                ->prefix('api/school')
                ->group(base_path('routes/api-school.php'));

            Route::middleware('api')
                ->prefix('api/teacher')
                ->group(base_path('routes/api-teacher.php'));

            Route::middleware('api')
                ->prefix('api/parent')
                ->group(base_path('routes/api-parent.php'));

            Route::middleware('api')
                ->prefix('api/student')
                ->group(base_path('routes/api-student.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinutes(5, 5)->by($request->ip())->response(function (Request $request) {
                return response('Too many login attempts! Try again in 5 minutes.', 429);
            });
        });
    }
}
