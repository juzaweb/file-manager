<?php

namespace Juzaweb\FileManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\FileManager\Models\Media;

class FolderController extends FileManagerController
{
    public function index()
    {
        $childrens = [];
        $folders = Media::whereNull('parent_id')
            ->where('type', '=', $this->getType())
            ->get(['id', 'name']);
        $storage = Media::sum('size');
        $total = disk_total_space(storage_path());

        foreach ($folders as $folder) {
            $childrens[] = (object) [
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
        $folder_name = $request->input('name');
        $parent_id = $request->input('working_dir');

        if (empty($folder_name)) {
            $this->throwError('folder-name');
        }

        if (Media::folderExists($folder_name, $parent_id)) {
            $this->throwError('folder-exist');
        }

        if (preg_match('/[^\w-]/i', $folder_name)) {
            $this->throwError('folder-alnum');
        }

        DB::beginTransaction();
        try {
            $model = new Media();
            $model->name = $folder_name;
            $model->type = $this->getType();
            $model->folder_id = $parent_id;
            $model->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return parent::$success_response;
    }
}
