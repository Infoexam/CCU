<?php

if (! function_exists('img_src')) {
    /**
     * 取得圖片網址
     *
     * @param int $timestamp
     * @param int $hash
     * @param string $extension
     * @param bool|false $thumbnail
     * @return string
     */
    function img_src($timestamp, $hash, $extension, $thumbnail = false) {
        $prefixDir = substr($timestamp, 0, 3);
        $filename = "{$timestamp}-{$hash}" . ($thumbnail ? '-s' : '') . ".{$extension}";

        return env('CDN_URL', '') . "/images/{$prefixDir}/{$filename}";
    }
}

if (! function_exists('img_path')) {
    /**
     * 取得圖片路徑
     *
     * @param int $timestamp
     * @param int $hash
     * @param string $extension
     * @param bool|false $thumbnail
     * @param bool $fromRoot
     * @return string
     */
    function img_path($timestamp, $hash, $extension, $thumbnail = false, $fromRoot = true) {
        $prefixDir = ($fromRoot ? config('filesystems.disks.local.root') : '') . '/images/' . substr($timestamp, 0, 3);
        $filename = "{$timestamp}-{$hash}" . ($thumbnail ? '-s' : '') . ".{$extension}";

        return "{$prefixDir}/{$filename}";
    }
}
