<?php

if (! function_exists('_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function _asset($path, $secure = null) {
        if (! app()->environment('production') || empty($cdn = config('infoexam.CDN_URL'))) {
            return asset($path, $secure);
        }

        return "{$cdn}/{$path}";
    }
}

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

        return config('infoexam.CDN_URL') . "/images/{$prefixDir}/{$filename}";
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

if (! function_exists('hash_equals')) {
    /**
     * Compare two strings in constant time.
     *
     * @link https://developer.wordpress.org/reference/functions/hash_equals/
     * @param string $a
     * @param string $b
     * @return bool
     */
    function hash_equals($a, $b) {
        $a_length = strlen($a);

        if ($a_length !== strlen($b)) {
            return false;
        }

        $result = 0;

        // Do not attempt to "optimize" this.
        for ( $i = 0; $i < $a_length; $i++ ) {
            $result |= ord( $a[ $i ] ) ^ ord( $b[ $i ] );
        }

        return $result === 0;
    }
}
