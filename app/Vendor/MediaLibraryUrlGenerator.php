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
        static $prefix = null;

        if (is_null($prefix)) {
            $prefix = $this->prefix();
        }

        return $prefix.'/'.$this->getPathRelativeToRoot();
    }

    /**
     * Get the url prefix.
     *
     * @return string
     */
    protected function prefix()
    {
        $prefix = config('laravel-medialibrary.media_url');

        return empty($prefix) ? $this->localStorage() : $prefix;
    }


    /**
     * Get image url from local filesystem.
     *
     * @return string
     */
    protected function localStorage()
    {
        $filesystem = config('laravel-medialibrary.defaultFilesystem');

        $root = config("filesystems.disks.{$filesystem}.root");

        return str_replace(public_path(), '', $root);
    }
}
