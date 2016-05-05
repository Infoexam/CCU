<?php

namespace App\Vendor;

use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;
use Spatie\MediaLibrary\UrlGenerator\UrlGenerator;

class MediaLibraryUrlGenerator extends BaseUrlGenerator implements UrlGenerator
{
    /**
     * Get the URL for the profile of a media item.
     *
     * @return string
     */
    public function getUrl() : string
    {
        $prefix = config('laravel-medialibrary.media_url');

        if (empty($prefix)) {
            $filesystem = config('laravel-medialibrary.defaultFilesystem');

            $root = config("filesystems.disks.{$filesystem}.root");

            $prefix = str_replace(public_path(), '', $root);
        }

        return $prefix.'/'.$this->getPathRelativeToRoot();
    }
}
