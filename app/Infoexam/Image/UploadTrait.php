<?php

namespace App\Infoexam\Image;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadTrait
{
    /**
     * @param UploadedFile|array $images
     */
    public function uploadImages($images)
    {
        $images = is_array($images) ? $images : [$images];

        foreach ($images as $image) {
            $info = $this->getImageInfo($image);

            $this->saveToDatabase($info)->moveToStorage($image, $info);
        }
    }

    /**
     * 創建資料
     *
     * @param array $info
     * @return $this
     */
    protected function saveToDatabase($info)
    {
        Image::create($info);

        return $this;
    }

    /**
     * 移動圖片
     *
     * @param UploadedFile $file
     * @param array $info
     */
    protected function moveToStorage(UploadedFile $file, $info)
    {
        $targetDir = file_build_path(config('infoexam.image_dir'), $info['prefix_dir']);

        $file->move($targetDir, "{$info['uploaded_at']->timestamp}-{$info['hash']}.{$info['extension']}");
    }

    /**
     * 取得圖片資訊
     *
     * @param UploadedFile $image
     * @return array
     */
    protected function getImageInfo(UploadedFile $image)
    {
        $now = Carbon::now();

        return [
            'uploaded_at' => $now,
            'hash' => random_int(1000000000, 4294967295),
            'extension' => $image->guessExtension(),
            'imageable_id' => $this->getAttribute('id'),
            'imageable_type' => get_called_class(),
            'prefix_dir' => substr($now->timestamp, 0, 3),
        ];
    }
}
