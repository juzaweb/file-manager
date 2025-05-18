<?php

namespace Juzaweb\FileManager\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Juzaweb\FileManager\Enums\MediaType;
use Juzaweb\FileManager\Http\Requests\StoreFolderRequest;
use Juzaweb\FileManager\Models\Media;

class FolderController extends FileManagerController
{
    public function index()
    {
        $childrens = [];
        $folders = Media::whereNull('parent_id')
            ->where('type', '=', MediaType::DIRECTORY)
            ->get(['id', 'name']);
        $storage = Media::sum('size');
        $total = disk_total_space(storage_path());

        foreach ($folders as $folder) {
            $childrens[] = (object) [
                'id' => $folder->id,
                'name' => $folder->name,
                'url' => $folder->id,
                'children' => [],
                'has_next' => false,
            ];
        }

        return view('file-manager::tree')
            ->with(
                [
                    'storage' => $storage,
                    'total' => $total,
                    'root_folders' => [
                        (object) [
                            'name' => 'Root',
                            'url' => '',
                            'children' => $childrens,
                            'has_next' => (bool) $childrens,
                        ],
                    ],
                ]
            );
    }

    public function store(StoreFolderRequest $request, string $disk)
    {
        $name = $request->input('name');
        $parentId = $request->input('working_dir');

        if (preg_match('/[^\w-]/i', $name)) {
            return response()->json([
                'success' => false,
                'message' => trans('file-manager::browser.error-folder-alnum')
            ]);
        }

        DB::beginTransaction();
        try {
            $model = new Media();
            $model->name = $name;
            $model->type = MediaType::DIRECTORY;
            $model->parent_id = $parentId;
            $model->disk = $disk;
            $model->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json(['success' => true]);
    }
}
