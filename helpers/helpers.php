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

if (! function_exists('format_size_units')) {
    /**
     * Convert a size in bytes to a human-readable string representation using appropriate units.
     *
     * @param int $bytes The size in bytes to be converted.
     * @param int $decimals The number of decimal places to use in the formatted output. Default is 2.
     * @return string A human-readable string representing the size in bytes using appropriate units (GB, MB, KB, bytes).
     */
    function format_size_units($bytes, int $decimals = 2): string
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, $decimals) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, $decimals) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, $decimals) . ' KB';
        } elseif ($bytes > 1) {
            $bytes .= ' bytes';
        } elseif ($bytes == 1) {
            $bytes .= ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('is_url')) {
    /**
     * Return true if string is a url
     *
     * @param string|null $url
     * @return bool
     */
    function is_url(?string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
