<?php

namespace App\Infoexam\User;

use App\Infoexam\Core\Entity;
use App\Infoexam\General\Category;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Entity implements AuthenticatableContract
{
    use Authenticatable, EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'social_security_number', 'gender_id', 'department_id', 'grade_id',
        'created_at', 'updated_at'
    ];

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['id', 'test_count', 'roles'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'email',
        'name', 'social_security_number', 'gender_id',
        'department_id', 'grade_id', 'class',
        'test_count', 'passed_score', 'passed_at'
    ];

    /**
     * 取得該使用者的成績資訊
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * 取得該使用者的性別
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender()
    {
        return $this->belongsTo(Category::class, 'gender_id');
    }

    /**
     * 取得該使用者的系所
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Category::class, 'department_id');
    }

    /**
     * 取得該使用者的年級
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade()
    {
        return $this->belongsTo(Category::class, 'grade_id');
    }
}
