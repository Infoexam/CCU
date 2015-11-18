<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use App\Infoexam\General\Category;
use App\Infoexam\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apply extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_applies';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'exam_list_id', 'apply_type_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'exam_list_id', 'apply_type_id', 'paid_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['paid_at', 'deleted_at'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['applied'];

    /**
     * 取得該報名的使用者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 取得該報名的報名方式（管理員 or 學生自行報名）
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applied()
    {
        return $this->belongsTo(Category::class, 'apply_type_id');
    }

    /**
     * 取得該報名的測驗結果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function result()
    {
        return $this->hasOne(Result::class, 'exam_apply_id');
    }

    /**
     * 取得該報名所屬的測驗
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function _list()
    {
        return $this->belongsTo(Lists::class, 'exam_list_id');
    }
}
