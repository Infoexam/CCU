<?php

namespace App\Accounts;

use App\Core\Entity;
use Carbon\Carbon;

class Receipt extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipts';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['receipt_no', 'receipt_date', 'category_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * Enable the revisioning or not.
     *
     * @var bool
     */
    public $revisionEnabled = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $receipt) {
            $receipt->setAttribute('created_at', Carbon::now());
        });
    }
}
