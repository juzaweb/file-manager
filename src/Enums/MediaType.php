<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\FileManager\Enums;

enum MediaType: string
{
    case FILE = 'file';
    case DIRECTORY = 'dir';
}
