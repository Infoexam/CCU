<?php

if (! function_exists('file_build_path')) {
    /**
     * Builds a file path with the appropriate directory separator.
     *
     * @param array|string ...$segments
     *
     * @return string
     */
    function file_build_path(...$segments)
    {
        return implode(DIRECTORY_SEPARATOR, $segments);
    }
}

if (! function_exists('_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param null $secure
     *
     * @return string
     */
    function _asset($path, $secure = null)
    {
        if (! app()->environment('production')) {
            if (app()->environment('development')) {
                $path = 'assets/'.$path;
            }

            return asset($path, $secure);
        }

        $elements = explode('/', $path);

        $filename = array_pop($elements);

        return implode('/', [
            config('infoexam.static_url'),
            implode('/', $elements),
            \App\Core\Entity::VERSION,
            $filename,
        ]);
    }
}
