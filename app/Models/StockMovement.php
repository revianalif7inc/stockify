<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'type', 'quantity', 'notes', 'user_id', 'status'];

    // Relationship: pergerakan stok terkait dengan produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship: pergerakan stok terkait dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
