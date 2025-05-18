<?php

use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'media'],
    function () {
        \Juzaweb\FileManager\Media::browser();
    }
);
