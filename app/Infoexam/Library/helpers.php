<?php

if (! function_exists('img_src')) {
    /**
     * 取得圖片路徑
     *
     * @param int $timestamp
     * @param int $hash
     * @param string $extension
     * @param bool|false $thumbnail
     * @return string
     */
    function img_src($timestamp, $hash, $extension, $thumbnail = false) {
        $prefixDir = substr($timestamp, 0, 3);
        $thumbnail = $thumbnail ? '-s' : '';
        $filename = "{$timestamp}-{$hash}{$thumbnail}.{$extension}";

        return env('CDN_URL', '') . "/images/{$prefixDir}/{$filename}";
    }
}
