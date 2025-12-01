<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relationship: Satu Kategori memiliki banyak Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Relationship: Satu Kategori memiliki banyak Atribut
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('order');
    }
}