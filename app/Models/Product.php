<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;
    // protected $appends = ['favourited'];

    protected $fillable = [
        'product_name',
        'brand',
        'category_id',
        'price',
        'description',
        'thumbnail_url',
        'created_at',
        'updated_at',
    ];

    public function quantities()
    {
        return $this->hasMany(Quantity::class);
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function favourites()
    {
        return $this->hasMany(User::class, 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
    // public function getFavouriteAttribute(){
    //     $favourited = Favourite::where(['product_id' => $this->id, 'customer_id' => Auth()->id()])->first();
    //     return $favourited ? true : false;
    // }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
