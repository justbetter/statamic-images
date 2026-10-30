<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages;

use Illuminate\Support\Facades\Blade;
use JustBetter\StatamicImages\Actions\BuildPicture;
use Override;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    #[Override]
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

    #[Override]
    public function bootAddon(): void
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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'justbetter-glide-images');

        $this->publishes([
            __DIR__.'/../resources/views/emails' => resource_path('views/emails'),
        ], 'justbetter-glide-images');

        return $this;
    }
}
