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
    }

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->app->singleton(Media::class, MediaRepository::class);

        $this->app->singleton(ImageConversion::class, ImageConversionRepository::class);
    }
}
