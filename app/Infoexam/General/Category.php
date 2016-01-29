<?php

namespace App\Infoexam\General;

use App\Infoexam\Core\Entity;
use Cache;

class Category extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['id', 'category'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category', 'name', 'remark'];

    /**
     * @param string $category
     * @param string $name
     * @param bool $firstId
     * @return \Illuminate\Database\Eloquent\Collection|int|static[]
     */
    public static function getCategories($category = '', $name = '', $firstId = false)
    {
        /** @var $categories \Illuminate\Database\Eloquent\Collection|static[] */

        $categories = Cache::remember('categoriesTable', static::MINUTES_PER_WEEK, function () {
            return static::all();
        });

        if (empty($category)) {
            return $categories;
        }

        $issetName = ! empty($name);

        $categories = $categories->filter(function ($item) use ($category, $issetName, $name) {
            /** @var $item Category */

            $filter = $item->getAttribute('category') === $category;

            return $issetName ? ($filter && $item->getAttribute('name') === $name) : $filter;
        });

        return $firstId
            ? $categories->first()->getAttribute('id')
            : ($issetName ? $categories->first() : $categories->values());
    }
}
