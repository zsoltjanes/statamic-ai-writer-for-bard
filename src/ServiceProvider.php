<?php

namespace Zsoltjanes\StatamicBardOpenai;

use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Zsoltjanes\StatamicBardOpenai\Controllers\StatamicBardController;

class ServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../dist/js/main.js',
    ];

    public function bootAddon()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/statamic-bard-openai.php', 'statamic-bard-openai'
        );

        $this->registerActionRoutes(function () {
            Route::post('/', [StatamicBardController::class, 'send']);
        });

        $this->publishes([
            __DIR__.'/../config/statamic-bard-openai.php' => config_path('statamic-bard-openai.php')
        ], 'statamic-bard-openai-config');

    }
}
