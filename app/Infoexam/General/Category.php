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
    protected $notAdminHidden = ['id', 'category', 'remark'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category', 'name', 'remark'];

    /**
     * @param string $category
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Collection|static[]|int
     */
    public static function getCategories($category = '', array $options = [])
    {
        /** @var $categories \Illuminate\Database\Eloquent\Collection|static[] */

        $categories = Cache::remember('categoriesTable', static::MINUTES_PER_WEEK, function () {
            return static::all();
        });

        if (empty($category)) {
            return $categories;
        }

        $categories = $categories->filter(function ($item) use ($category, $options) {
            /** @var $item Category */

            $filter = $item->getAttribute('category') === $category;

            return isset($options['name']) ? ($filter && $item->getAttribute('name') === $options['name']) : $filter;
        });

        return isset($options['firstId']) ? $categories->first()->getAttribute('id') : $categories;
    }
}
