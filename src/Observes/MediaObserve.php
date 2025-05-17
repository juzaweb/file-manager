<?php

namespace Juzaweb\FileManager\Observes;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\FileManager\Models\Media;

class MediaObserve
{
    /**
     * @param  Model|Media  $media
     * @return void
     */
    public function forceDeleted(Model $media): void
    {
        collect($media->conversions ?? [])->each(
            fn($conversion) => $media->filesystem()->delete($conversion)
        );
    }
}
