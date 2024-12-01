<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Store;
use App\Observers\ProductObserver;
use App\Observers\StoreObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Meilisearch\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->observers();
        $this->rateLimiters();
        $this->milisearch();
        $this->routes();

        Model::shouldBeStrict(! app()->environment('production'));
        Model::preventLazyLoading(! app()->environment('production'));

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

    }

    public function routes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/Api/auth.php'));
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/Api/store.php'));
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/Api/product.php'));
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/Api/user.php'));
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/Api/category.php'));
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/Api/favourite.php'));
    }

    public function milisearch()
    {

        // Connect to Meilisearch
        $client = new Client('http://127.0.0.1:7700'); // Replace with your Meilisearch server URL
        $index = $client->index('products');

        // Update filterable attributes to include 'id' and 'store_id'
        $index->updateFilterableAttributes(['id', 'store_id']);
    }

    public function observers(): void
    {
        Product::observe(ProductObserver::class);
        Store::observe(StoreObserver::class);
    }

    public function rateLimiters(): void
    {

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('send_confirmation_code', function (Request $request) {
            return [
                Limit::perMinutes(30, 5)->by($request->ip()), // Limit to 1 request every 30 minutes
                Limit::perDay(10)->by($request->ip()),         // Limit to 5 requests per day
            ];
        });
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(30, 2)->by($request->user()?->id ?: $request->ip());
        });
        RateLimiter::for('change_password', function (Request $request) {
            return Limit::perDay(5)->by($request->user()?->id);
        });
    }
}
