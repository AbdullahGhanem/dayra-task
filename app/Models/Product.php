<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'quantity',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['carts'];

    /**
     * Get the Supplier that owns the Product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }    

    /**
     * Get the Category that owns the Product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart that owns the Cart.
     */
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity')->withTimestamps();
    } 
}
