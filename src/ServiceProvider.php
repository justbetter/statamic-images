<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\StatamicImages\Actions\BuildPicture;
use Override;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this
            ->registerConfig()
            ->registerActions();
    }

    protected function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__.'/../config/statamic-glide-images.php', 'statamic-glide-images');

        return $this;
    }

    protected function registerActions(): static
    {
        BuildPicture::bind();

        return $this;
    }

    public function boot(): void
    {
        $this
            ->bootBladeComponents()
            ->bootConfig()
            ->bootViews();
    }

    protected function bootBladeComponents(): static
    {
        Blade::componentNamespace('JustBetter\\StatamicImages\\View\\Components', 'justbetter');

        return $this;
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__.'/../config/statamic-glide-images.php' => config_path('statamic-glide-images.php'),
        ], 'config');

        return $this;
    }

    protected function bootViews(): static
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'justbetter');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
        ], 'justbetter');

        return $this;
    }
}
