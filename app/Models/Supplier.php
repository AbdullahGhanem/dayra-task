<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
    /**
     * Get the products for the Supplier.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
