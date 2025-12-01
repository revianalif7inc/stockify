<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'attribute_id', 'value'];

    // Relationship: Nilai atribut milik satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship: Nilai atribut terkait dengan atribut
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }
}
