<?php

namespace Juzaweb\FileManager\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Juzaweb\Core\Models\Authenticatable;
use Juzaweb\FileManager\MediaUploader;
use Juzaweb\FileManager\Models\Media;

/**
 * @mixin Authenticatable
 */
trait MediaUploadable
{
    public function upload(
        string|UploadedFile $file,
        string $disk = 'public',
        ?string $name = null
    ): MediaUploader|Media {
        return MediaUploader::make($file, $disk, $name)->user($this);
    }

    public function uploadedMedia(): MorphMany
    {
        return $this->morphMany(Media::class, 'uploadable', 'uploaded_by_type', 'uploaded_by_id', 'id');
    }
}
