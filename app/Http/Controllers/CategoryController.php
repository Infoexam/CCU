<?php

namespace App\Http\Controllers;

use Infoexam\Eloquent\Models\Category;

class CategoryController extends Controller
{
    /**
     * Get the filtered categories.
     *
     * @param string $category
     * @param string|null $name
     *
     * @return \Illuminate\Database\Eloquent\Collection|Category
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
