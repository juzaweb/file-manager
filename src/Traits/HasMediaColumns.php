<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\FileManager\Traits;

/**
 * @property $mediaColumns
 */
trait HasMediaColumns
{
    public function getMediaColumns(): array
    {
        if (isset($this->mediaColumns)) {
            return $this->mediaColumns;
        }

        return [];
    }
}
