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
    public function register(): void {}

    public function boot(): void
    {
        $this->observers();
        $this->rateLimiters();
        $this->meilisearch();
        $this->routes();
        $this->productionConfigurations();
        $this->PassWordConfigurations();

    }

    public function routes(): void
    {
        $apiRouteFiles = [
            'auth.php',
            'store.php',
            'product.php',
            'user.php',
            'category.php',
            'favourite.php',
            'statistics.php',
            'cart.php',
        ];

        foreach ($apiRouteFiles as $routeFile) {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path("routes/Api/{$routeFile}"));
        }

    }

    public function meilisearch(): void
    {

        // Connect to Meilisearch
        $client = new Client(env('MEILISEARCH_URL', 'http://127.0.0.1:7700')); // Replace with your Meilisearch server URL
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
        if (app()->environment('production')) {

        }
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

    public function productionConfigurations(): void
    {
        Model::shouldBeStrict(! app()->environment('production'));
        Model::preventLazyLoading(! app()->environment('production'));

    }

    public function PassWordConfigurations(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }
}
