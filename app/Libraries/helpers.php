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
        return \App\Categories\Category::getCategories($category)->random()->getKey();
    }
}
