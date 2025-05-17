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
use Juzaweb\FileManager\Http\Controllers\FileManagerController;
use Juzaweb\FileManager\Http\Controllers\UploadController;
use Juzaweb\FileManager\Http\Controllers\ItemsController;
use Juzaweb\FileManager\Http\Controllers\FolderController;
use Juzaweb\FileManager\Http\Controllers\DeleteController;

class Media
{
    public static function browser(): void
    {
        Route::get('/{disk}', [FileManagerController::class, 'index']);

        Route::get('/{disk}/errors', [FileManagerController::class, 'getErrors']);

        Route::get('/{disk}/items', [ItemsController::class, 'getItems']);

        Route::post('/{disk}/newfolder', [FolderController::class, 'addfolder']);

        Route::get('/{disk}/folders', [FolderController::class, 'getFolders']);

        Route::post('/{disk}/delete', [DeleteController::class, 'delete']);

        Route::post('/{disk}/upload', [UploadController::class, 'upload'])
            ->name('media.upload');

        Route::post('/{disk}/import', [UploadController::class, 'import'])
            ->name('media.import');
    }
}
