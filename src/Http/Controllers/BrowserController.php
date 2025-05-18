<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\FileManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Juzaweb\FileManager\Models\Media;

class BrowserController extends FileManagerController
{
    public function index(Request $request, string $disk): View
    {
        if ($type = $this->getType()) {
            $mimeTypes = config("media.types.{$type}");
        } else {
            $mimeTypes = config("media.disks.{$disk}.mime_types");
        }

        $maxSize = config("media.disks.{$disk}.max_size");
        $multiChoose = $request->get('multichoose', 0);

        return view(
            'file-manager::index',
            compact(
                'mimeTypes',
                'maxSize',
                'multiChoose',
                'disk'
            )
        );
    }

    public function items(Request $request, string $disk): array
    {
        $type = $this->getType();
        $currentPage = $request->input('page', 1);
        $perPage = 15;

        $workingDir = $request->get('working_dir');

        $folders = collect([]);
        if ($currentPage == 1) {
            $folders = Media::where('parent_id', '=', $workingDir)
                ->where('disk', '=', $disk)
                ->orderBy('name', 'ASC')
                ->get(['id', 'name']);
        }

        $query = Media::where('parent_id', '=', $workingDir)
            ->where('disk', '=', $disk)
            ->when(
                $type,
                fn ($q) => $q->whereIn('extension', config("media.types.{$type}"))
            )
            ->orderBy('id', 'DESC');

        $totalFiles = $query->count(['id']);
        $files = $query->paginate($perPage - $folders->count());

        $items = [];
        foreach ($folders as $folder) {
            $items[] = [
                'id' => $folder->id,
                'icon' => 'fa-folder-o',
                'is_file' => false,
                'is_image' => false,
                'name' => $folder->name,
                'thumb_url' => asset('jw-styles/juzaweb/images/folder.png'),
                'time' => false,
                'url' => $folder->id,
                'path' => $folder->id,
            ];
        }

        foreach ($files as $file) {
            $items[] = [
                'id' => $file->id,
                'icon' => $file->type == 'image' ? 'fa-image' : 'fa-file',
                'is_file' => true,
                'path' => $file->path,
                'is_image' => $file->type == 'image',
                'name' => $file->name,
                'thumb_url' => $file->type == 'image' ? media_url($file->path) : null,
                'time' => strtotime($file->created_at),
                'url' => media_url($file->path),
            ];
        }

        return [
            'items' => $items,
            'paginator' => [
                'current_page' => $currentPage,
                'total' => $totalFiles + $folders->count(),
                'per_page' => $perPage,
            ],
            'display' => 'grid',
            'working_dir' => $workingDir,
        ];
    }

    public function getErrors(): array
    {
        $errors = [];
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            $errors[] = trans('cms::filemanager.message_extension_not_found', ['name' => 'gd']);
        }

        if (! extension_loaded('exif')) {
            $errors[] = trans('cms::filemanager.message_extension_not_found', ['name' => 'exif']);
        }

        if (! extension_loaded('fileinfo')) {
            $errors[] = trans('cms::filemanager.message_extension_not_found', ['name' => 'fileinfo']);
        }

        return $errors;
    }

    public function delete(Request $request, string $disk)
    {
        $itemNames = $request->post('items');
        $errors = [];

        foreach ($itemNames as $file) {
            if (is_null($file)) {
                $errors[] = parent::error('folder-name');
                continue;
            }

            $is_directory = $this->isDirectory($file);
            if ($is_directory) {
                Media::where(['disk' => $disk, 'id' => $file])->first()->deleteFolder();
            } else {
                $file_path = $this->getPath($file);
                Media::where('path', '=', $file_path)
                    ->first()
                    ->delete();
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }

        return static::$success_response;
    }

    public function showFile($path)
    {
        $storage = Storage::disk(config('juzaweb.filemanager.disk'));

        if (! $storage->exists($path)) {
            abort(404);
        }

        return response($storage->get($path))
            ->header('Content-Type', $storage->mimeType($path));
    }
}
