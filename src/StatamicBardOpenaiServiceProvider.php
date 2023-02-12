<?php

namespace Zsoltjanes\StatamicBardOpenai;

use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Zsoltjanes\StatamicBardOpenai\Controllers\StatamicBardController;

class StatamicBardOpenaiServiceProvider extends AddonServiceProvider
{
    protected $scripts = [
        __DIR__.'/../dist/js/main.js',
    ];

    protected $publishables = [

    ];

    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/statamic-bard-ai'),
        ], 'statamic-bard-ai');


        $this->mergeConfigFrom(
            __DIR__.'/../config/statamic-bard-openai.php', 'statamic-bard-openai'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../config/statamic-bard-openai-settings.php', 'statamic-bard-openai-settings'
        );
    }

    public function bootAddon()
    {
        $this->registerActionRoutes(function () {
            Route::post('/', [StatamicBardController::class, 'send']);
        });
    }
}
