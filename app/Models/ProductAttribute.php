<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'type', 'options', 'is_required', 'order'];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean'
    ];

    // Relationship: Atribut milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: Atribut memiliki banyak nilai produk
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }
}
