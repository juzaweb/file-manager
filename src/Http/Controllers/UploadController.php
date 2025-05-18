<?php

namespace Juzaweb\FileManager\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\FileManager\Events\UploadFileSuccess;
use Juzaweb\FileManager\Http\Requests\ImportRequest;
use Juzaweb\FileManager\MediaUploader;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadController extends FileManagerController
{
    protected array $errors = [];

    public function upload(Request $request, string $disk): JsonResponse
    {
        $folderId = $request->input('working_dir');

        if (!array_key_exists($disk, config('media.disks'))) {
            return $this->responseUpload([trans('file-manager::browser.invalid_disk') ]);
        }

        if (empty($folderId)) {
            $folderId = null;
        }

        try {
            $receiver = new FileReceiver('upload', $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();
            if ($save->isFinished()) {
                $uploader = new MediaUploader($save->getFile(), $disk);

                $uploader->folder($folderId);

                $file = $uploader->upload();

                event(new UploadFileSuccess($file));

                return $this->responseUpload($this->errors);
            }

            $handler = $save->handler();

            return response()->json(
                [
                    "done" => $handler->getPercentageDone(),
                    'status' => true,
                ]
            );
        } catch (Exception $e) {
            report($e);
            $this->errors[] = $e->getMessage();
            return $this->responseUpload($this->errors);
        }
    }

    public function import(ImportRequest $request, string $disk): JsonResponse
    {
        if (! config('media.upload_from_url')) {
            abort(403);
        }

        $folderId = $request->input('working_dir');
        $download = (bool) $request->input('download');

        if (empty($folderId)) {
            $folderId = null;
        }

        if (!array_key_exists($disk, config('media.disks'))) {
            return $this->responseUpload([trans('cms::message.invalid_disk') ]);
        }

        DB::beginTransaction();
        try {
            $file = new MediaUploader($request->input('url'), $disk);
            $file->folder($folderId);
            $file->upload();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return $this->responseUpload([$e->getMessage()]);
        }

        return response()->json(
            [
                'success' => true,
                'message' => trans('cms::message.upload_successfull'),
            ]
        );
    }

    protected function responseUpload($error): JsonResponse
    {
        $response = count($error) > 0 ? $error : parent::$success_response;

        return response()->json($response, $error ? 422 : 200);
    }
}
