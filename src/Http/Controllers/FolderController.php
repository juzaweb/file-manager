<?php

namespace Juzaweb\FileManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\FileManager\Enums\MediaType;
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

    public function store(Request $request): string
    {
        $name = $request->input('name');
        $parentId = $request->input('working_dir');

        if (empty($name)) {
            $this->throwError('folder-name');
        }

        if (Media::folderExists($name, $parentId)) {
            $this->throwError('folder-exist');
        }

        if (preg_match('/[^\w-]/i', $name)) {
            $this->throwError('folder-alnum');
        }

        DB::beginTransaction();
        try {
            $model = new Media();
            $model->name = $name;
            $model->type = $this->getType();
            $model->folder_id = $parentId;
            $model->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return parent::$success_response;
    }
}
