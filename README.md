## About
The file manager intended for using Laravel with CKEditor / TinyMCE. File manager in table database, do not browse on the server folder.
![File Manager demo](https://i.imgur.com/1SgXwkM.png)

### Features
- [x] DB media and media folder
- [x] Chunk upload support
- [x] CKEditor and TinyMCE integration
- [x] Uploading validation
- [x] Cropping and resizing of images
- [x] Add custom support type
- [x] Image optimize after upload
- [ ] Multi media select

## Install
- Install package
```
composer require tadcms/filemanager
```

- Publish the package’s config and assets:
```
php artisan vendor:publish --provider="Tadcms\FileManager\Providers\FileManagerServiceProvider" --tag=config
php artisan vendor:publish --provider="Tadcms\FileManager\Providers\FileManagerServiceProvider" --tag=assets
```
- Migration
```
php artisan migrate
```

- Create symbolic link:
```
php artisan storage:link
```

- Edit routes/web.php
```
Route::group(['prefix' => 'file-manager', 'middleware' => ['web', 'auth']], function (){
    \Tadcms\FileManager\Routes::web();
});
```

## Usage
- [Editor Integration](https://github.com/tadcms/filemanager/blob/master/docs/usage-editor.md)
- [Standalone Integration](https://github.com/tadcms/filemanager/blob/master/docs/usage-editor.md)
- [JavaScript integration](https://github.com/tadcms/filemanager/blob/master/docs/javascript-integration.md)

- Helper class

Add media with ``\Illuminate\Http\UploadedFile``

```
use Tadcms\FileManager\Facades\FileManager;

FileManager::withResource(request()->file('upload_file'))
    ->setFolder($folder_id)
    ->setType($type)
    ->save();
```

Add media with url

```
use Tadcms\FileManager\Facades\FileManager;

FileManager::withResource($urlFile)
    ->setFolder($folder_id)
    ->setType($type)
    ->save();
```

Add media with path
```
use Tadcms\FileManager\Facades\FileManager;

FileManager::withResource($pathFile)
    ->setFolder($folder_id)
    ->setType($type)
    ->save();
```

**Params:**
```
$folder_id: Id lfm_folder_media table
$type: image/file or customs your type
```

## Configs
```
<?php

return [
    'disks' => [
        'public' => [
            /** Mime types can be uploaded */
            'mime_types' => [
                'image/png',
                'image/jpeg',
                'image/jpg',
                'image/gif',
                'image/svg+xml',
                'image/svg',
                'video/quicktime',
                'video/webm',
                'video/mp4',
                'audio/mp3',
                'audio/ogg',
                'image/webp',
            ],

            /** Max size of file in bytes */
            'max_size' => 1024 * 1024 * 15, // 15 MB
        ],
    ],

    /**
     * On/Off Optimize uploaded images
     *
     */
    'image-optimize' => env('JW_MEDIA_IMAGE_OPTIMIZE', false),

    /**
     * Mime types for images
     */
    'image_mime_types' => [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/svg+xml',
        'image/svg',
        'image/webp',
    ],

    'models' => [
        'media' => \Juzaweb\FileManager\Models\Media::class,
    ],
];
```

## Credits
[Laravel File Manager](https://github.com/UniSharp/laravel-filemanager)

## License

The Laravel File Manager package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
