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
- [x] Multi media select

## Install
- Install package
```
composer require juzaweb/file-manager
```

- Publish the package’s config and assets:
```
php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=assets
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
    \Juzaweb\FileManager\Media::browser();
});
```

## Usage
Updating...

## Credits
[Laravel File Manager](https://github.com/UniSharp/laravel-filemanager)

## License

The Laravel File Manager package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
