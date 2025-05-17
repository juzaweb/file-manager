<?php

namespace Juzaweb\FileManager\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Juzaweb\FileManager\Models\Media;
use Juzaweb\Core\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function getDownload()
    {
        $file = $this->getPath(request()->get('file'));
        $data = Media::where('path', '=', $file)->first(['name']);

        $path = Storage::disk(config('juzaweb.filemanager.disk'))->path($file);
        if ($data) {
            return response()->download($path, $data->name);
        }

        return response()->download($path);
    }
}
