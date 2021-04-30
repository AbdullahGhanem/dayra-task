<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    /**
     * Get the cart's Total.
     *
     * @param  string  $value
     * @return string
     */
    public function getTotalAttribute($value)
    {
        $total = 0;
        foreach ($this->products as $product) {
           $total += $product->price * $product->pivot->quantity;
        }
        return $total;
    }

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
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    } 
}
