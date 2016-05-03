<?php

namespace App\Http\Controllers\Api\V1;

use App\Categories\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store()
    {
        //
    }

    /**
     * Get the filter result.
     *
     * @param string $category
     * @param string|null $name
     * @return \Illuminate\Database\Eloquent\Collection|mixed|static[]
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

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
