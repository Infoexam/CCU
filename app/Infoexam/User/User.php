<?php

namespace App\Infoexam\User;

use App\Infoexam\Core\Entity;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'email',
        'name', 'social_security_number', 'gender',
        'department', 'grade', 'class',
        'test_count', 'passed_score', 'passed_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * 取得該使用者的成績資訊
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }
}
