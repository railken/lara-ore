<?php

namespace Railken\LaraOre;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    public $app;

    /**
     * Current version.
     *
     * @var string
     */
    public $version;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $router->middleware([
            \Barryvdh\Cors\HandleCors::class,
            \Railken\LaraOre\Api\Http\Middleware\LoggerMiddleware::class,
        ]);

        $router->aliasMiddleware('admin', \Railken\LaraOre\Api\Http\Middleware\AdminMiddleware::class);

        config(['auth.guards.api.driver' => 'passport']);
        config(['auth.guards.api.provider' => 'users']);
        config(['auth.providers.users.driver' => 'eloquent']);
        config(['auth.providers.users.model' => \Railken\LaraOre\Core\User\User::class]);

        $callback = function ($router) {
            $router->all();
        };

        $this->loadRoutesFrom(__DIR__.'/Api/Resources/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Resources/Migrations');

        Schema::defaultStringLength(191);

        $options = array_merge([
            'namespace' => '\Laravel\Passport\Http\Controllers',
            'prefix'    => 'api/v1/oauth',
        ], []);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });

        Passport::tokensExpireIn(now()->addDays(15));

        Passport::refreshTokensExpireIn(now()->addDays(30));

        if (Schema::hasTable('configs')) {
            $configs = (new \Railken\LaraOre\Core\Config\ConfigManager())->getRepository()->findToLoad();

            $configs = $configs->mapWithKeys(function ($config, $key) {
                return [$config->resolveKey($config->key) => $config->value];
            })->toArray();

            config($configs);
        }

        if (Schema::hasTable('disks')) {
            $disks = (new \Railken\LaraOre\Core\Disk\DiskManager())->getRepository()->newQuery()->get();

            foreach ($disks as $disk) {
                $disk->reloadConfig();
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
