<?php

namespace Juzaweb\FileManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Juzaweb\FileManager\Models\Media;

class FileManagerController extends Controller
{
    protected static string $success_response = 'OK';

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

    public function getItems(Request $request): array
    {
        $type = $this->getType();
        $extensions = $this->getTypeExtensions($type);
        $currentPage = $request->input('page', 1);
        $perPage = 15;

        $workingDir = $request->get('working_dir');
        $disk = $request->get('disk') ?? config('juzaweb.filemanager.disk');

        $folders = collect([]);
        if ($currentPage == 1) {
            $folders = Media::where('parent_id', '=', $workingDir)
                ->where('disk', '=', $disk)
                ->orderBy('name', 'ASC')
                ->get(['id', 'name']);
        }

        $query = Media::where('parent_id', '=', $workingDir)
            ->where('disk', '=', $disk)
            ->whereIn('extension', $extensions)
            ->orderBy('id', 'DESC');

        $totalFiles = $query->count(['id']);
        $files = $query->paginate($perPage - $folders->count());

        $items = [];
        foreach ($folders as $folder) {
            $items[] = [
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
                'icon' => $file->type == 'image' ? 'fa-image' : 'fa-file',
                'is_file' => true,
                'path' => $file->path,
                'is_image' => $file->type == 'image',
                'name' => $file->name,
                'thumb_url' => $file->type == 'image' ? upload_url($file->path) : null,
                'time' => strtotime($file->created_at),
                'url' => upload_url($file->path),
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

    public function throwError($type, $variables = []): void
    {
        throw new \Exception(trans('cms::filemanager.error_' . $type, $variables));
    }

    public function delete(Request $request)
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
                Media::find($file)->deleteFolder();
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

    protected function getTypeExtensions(string $type)
    {
        $extensions = config("juzaweb.filemanager.types.{$type}.extensions");
        if (empty($extensions)) {
            $extensions = match ($type) {
                'file' => Facades::defaultFileExtensions(),
                'image' => Facades::defaultImageExtensions(),
            };
        }

        return $extensions;
    }

    protected function getType(): string
    {
        $type = strtolower(request()->get('type'));

        return Str::singular($type);
    }

    protected function getPath($url): string
    {
        $explode = explode('uploads/', $url);
        if (isset($explode[1])) {
            return $explode[1];
        }

        return $url;
    }

    protected function isDirectory($file): bool
    {
        if (is_numeric($file)) {
            return true;
        }

        return false;
    }
}
