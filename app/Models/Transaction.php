<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Get user.
     *
     * @return Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get order.
     *
     * @return Collection
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * boot method.
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function($trans)
        {
            $trans->balance = ($trans->type == 'debit') ? $trans->user->balance - $trans->amount : $trans->user->balance + $trans->amount;
        });
    }
}
