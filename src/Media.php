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
        Route::get('/', [FileManagerController::class, 'index']);

        Route::get('/errors', [FileManagerController::class, 'getErrors']);

        Route::any('/upload', [UploadController::class, 'upload'])->name('media.upload');

        Route::any('/import', [UploadController::class, 'import'])->name('media.import');

        Route::get('/items', [ItemsController::class, 'getItems']);

        Route::post('/newfolder', [FolderController::class, 'addfolder']);

        Route::get('/folders', [FolderController::class, 'getFolders']);

        Route::post('/delete', [DeleteController::class, 'delete']);
    }
}
