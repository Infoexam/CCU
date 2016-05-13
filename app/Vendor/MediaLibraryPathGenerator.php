<?php

namespace App\Vendor;

use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class MediaLibraryPathGenerator implements PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     *
     * @param \Spatie\MediaLibrary\Media $media
     *
     * @return string
     */
    public function getPath(Media $media) : string
    {
        return $this->getPrefix($media).'/'.$media->getAttribute('id').'/';
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     *
     * @param \Spatie\MediaLibrary\Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media) : string
    {
        return $this->getPath($media).'conversions/';
    }

    /**
     * Get the path prefix.
     *
     * @param Media $media
     *
     * @return int
     */
    protected function getPrefix(Media $media)
    {
        return intval(floor($media->getAttribute('id') / 1000));
    }
}
