<?php

namespace App\Accounts;

use App\Categories\Category;
use App\Core\Entity;
use App\Exams\Apply;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends Entity implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token', 'department_id', 'grade_id',
        'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['password', 'version', 'name', 'email', 'gender', 'department_id', 'grade_id', 'class'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['passed_at'];

    /**
     * Enable the revisioning or not.
     *
     * @var bool
     */
    public $revisionEnabled = true;

    /**
     * Get the user's password.
     *
     * @param string $value
     *
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Set the user's password.
     *
     * @param string $value
     *
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    public function department()
    {
        return $this->belongsTo(Category::class, 'department_id');
    }

    public function grade()
    {
        return $this->belongsTo(Category::class, 'grade_id');
    }

    /**
     * 取得使用者測驗通過狀態.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * 取得使用者繳費紀錄.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * 取得使用者報名紀錄.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    /**
     * 檢查使用者是否具有該身份.
     *
     * @param string|array $role
     * @return bool
     */
    public function own($role)
    {
        if (! $this->exists) {
            return false;
        }

        if (is_array($role)) {
            return in_array($this->getAttribute('role'), $role, true);
        }

        return $role === $this->getAttribute('role');
    }
}
