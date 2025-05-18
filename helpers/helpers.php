<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

if (! function_exists('media_url')) {
    function media_url(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }
}

if (! function_exists('media')) {
    /**
     * @return \Juzaweb\FileManager\MediaRepository
     */
    function media(): \Juzaweb\FileManager\MediaRepository
    {
        return app(\Juzaweb\FileManager\Contracts\Media::class);
    }
}
