<?php

namespace Juzaweb\FileManager\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\FileManager\Contracts\ImageConversion;
use Juzaweb\FileManager\Contracts\Media;
use Juzaweb\FileManager\ImageConversionRepository;
use Juzaweb\FileManager\MediaRepository;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'file-manager');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'file-manager');
    }

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->mergeConfigFrom(__DIR__ . '/../../config/media.php', 'media');

        $this->publishes([
            __DIR__ . '/../../config/media.php' => config_path('media.php'),
        ], 'media-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/file-manager'),
        ], 'media-lang');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/file-manager'),
        ], 'media-views');

        $this->publishes([
            __DIR__ . '/../../assets/public' => public_path('vendor/file-manager'),
        ], 'media-assets');

        $this->app->singleton(Media::class, MediaRepository::class);

        $this->app->singleton(ImageConversion::class, ImageConversionRepository::class);
    }
}
