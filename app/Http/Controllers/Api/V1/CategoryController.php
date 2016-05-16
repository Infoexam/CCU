<?php

namespace App\Http\Controllers\Api\V1;

use App\Categories\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Get the filtered categories.
     *
     * @param string $category
     * @param string|null $name
     * @return \Dingo\Api\Http\Response
     */
    public function filter($category, $name = null)
    {
        $categories = Category::where('category', $category);

        if (! is_null($name)) {
            $categories = $categories->where('name', $name);
        }

        $categories = $categories->get();

        return 1 === $categories->count() ? $categories->first() : $categories;
    }
}
