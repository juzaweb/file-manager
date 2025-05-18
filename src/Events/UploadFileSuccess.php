<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\FileManager\Events;

use Juzaweb\FileManager\Models\Media;

class UploadFileSuccess
{
    public function __construct(public Media $media)
    {
    }
}
