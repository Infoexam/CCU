<?php

namespace App\Media;

trait UploadImagesTrait
{
    /**
     * Upload images to server.
     *
     * @param array|\Symfony\Component\HttpFoundation\File\UploadedFile $files
     * @param string $collection
     *
     * @return array
     */
    public function uploadImages($files, $collection)
    {
        $media = [];

        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $media[] = $this->addMedia($file)
                ->setFileName('origin.'.$file->guessExtension())
                ->toMediaLibrary($collection);
        }

        return $media;
    }
}
