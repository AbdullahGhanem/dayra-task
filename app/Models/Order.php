<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['total', 'user_id'];

    /**
     * Get the USer that owns the Cart.
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    } 

    /**
     * Get the products that owns the Cart.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price')->withTimestamps();
    } 
}
