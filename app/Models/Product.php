<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'sku',
        'name',
        'description',
        'purchase_price',
        'selling_price',
        'current_stock',
        'min_stock',
        'image'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/products/' . $this->image);
        }
        return asset('images/no-image.svg');
    }

    // Relationship: Produk dimiliki oleh satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: Produk disuplai oleh satu Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship: Produk memiliki banyak pergerakan stok
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Relationship: Produk memiliki banyak nilai atribut
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Accessor for SKU. If there's no `sku` column, generate a stable SKU from the id.
     */
    public function getSkuAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        // If id is not set (new model) return placeholder, otherwise generate padded SKU
        if (empty($this->id)) {
            return null;
        }

        return 'SKU' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Ensure `sku` is included in array/json representations (useful for APIs)
     */
    protected $appends = ['sku'];
}