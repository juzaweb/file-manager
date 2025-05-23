<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\FileManager;

use Illuminate\Support\Facades\Route;
use Juzaweb\FileManager\Http\Controllers\BrowserController;
use Juzaweb\FileManager\Http\Controllers\UploadController;
use Juzaweb\FileManager\Http\Controllers\FolderController;

class Media
{
    public static function browser(): void
    {
        Route::get('/{disk}/browser', [BrowserController::class, 'index']);

        Route::get('/{disk}/browser/errors', [BrowserController::class, 'getErrors']);

        Route::get('/{disk}/browser/items', [BrowserController::class, 'items']);

        Route::post('/{disk}/browser/folders', [FolderController::class, 'store']);

        Route::get('/{disk}/browser/folders', [FolderController::class, 'index']);

        Route::post('/{disk}/browser/delete', [BrowserController::class, 'delete']);

        Route::post('/{disk}/upload', [UploadController::class, 'upload'])
            ->name('media.upload');

        Route::post('/{disk}/import', [UploadController::class, 'import'])
            ->name('media.import');
    }
}
