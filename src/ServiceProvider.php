<?php

namespace Zsoltjanes\StatamicBardOpenai;

use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Zsoltjanes\StatamicBardOpenai\Controllers\StatamicBardController;

class ServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'statamic-bard-openai';

    protected $scripts = [
        __DIR__.'/../dist/js/main.js',
    ];

    public function bootAddon()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/blueprints/globals/statamic_bard_openai.yaml' => resource_path('blueprints/globals/statamic_bard_openai.yaml'),
            ], 'statamic-bard-openai-blueprints');
        }

        $this->registerActionRoutes(function () {
            Route::get('/presets', [StatamicBardController::class, 'presets']);
            Route::post('/', [StatamicBardController::class, 'send']);
        });
    }
}
