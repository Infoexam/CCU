<?php

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

        return implode('/', [
            config('infoexam.static_url'),
            'assets',
            json_decode(File::get(base_path('composer.json'), true), true)['version'],
            $path,
        ]);
    }
}

if (! function_exists('random_category')) {
    /**
     * Get random category from specific group.
     *
     * @param string $category
     *
     * @return string
     */
    function random_category($category)
    {
        return \Infoexam\Eloquent\Models\Category::getCategories($category)->random()->getKey();
    }
}
