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
        if (! app()->environment('production') || empty($cdn = config('infoexam.static_url'))) {
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
     * @return string
     */
    function img_src($timestamp, $hash, $extension) {
        $prefixDir = substr($timestamp, 0, 3);

        $filename = "{$timestamp}-{$hash}.{$extension}";

        return config('infoexam.static_url') . "/assets/images/{$prefixDir}/{$filename}";
    }
}

if (! function_exists('img_path')) {
    /**
     * 取得圖片路徑
     *
     * @param int $timestamp
     * @param int $hash
     * @param string $extension
     * @return string
     */
    function img_path($timestamp, $hash, $extension) {
        $filename = "{$timestamp}-{$hash}.{$extension}";

        return file_build_path(config('infoexam.image_dir'), substr($timestamp, 0, 3), $filename);
    }
}

if (! function_exists('hash_equals')) {
    /**
     * Compare two strings in constant time.
     *
     * @param string $a
     * @param string $b
     * @return bool
     *
     * @link https://developer.wordpress.org/reference/functions/hash_equals/
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

if (! function_exists('file_build_path')) {
    /**
     * Builds a file path with the appropriate directory separator.
     *
     * @return string
     */
    function file_build_path() {
        return implode(DIRECTORY_SEPARATOR, func_get_args());
    }
}
