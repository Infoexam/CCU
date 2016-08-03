<?php

namespace App\Vendor;

use Hashids\Hashids;
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
        return $this->getPrefix($media).'/'.$this->getIdentity($media).'/';
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
        return $this->getPath($media);
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
        return substr($media->getAttribute('created_at')->timestamp, 0, 4);
    }

    /**
     * Get the media identity.
     *
     * @param Media $media
     *
     * @return string
     */
    protected function getIdentity(Media $media)
    {
        return implode('-', [
            substr($media->getAttribute('created_at')->timestamp, 4),
            app(Hashids::class)->encode($media->getAttribute('id'), $media->getAttribute('model_id')),
        ]);
    }
}
