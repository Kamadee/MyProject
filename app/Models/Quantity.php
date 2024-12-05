<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'quantity',
        'created_at',
        'updated_at',
    ];
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    
}