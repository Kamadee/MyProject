<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'date_order',
        'received_date',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'order_status' => 'Chờ xử lý',
        'recipient_name' => 'Unknown',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor
    public function getRecipientNameAttribute($value)
    {
        return $value;  // Trả về giá trị bình thường của `recipient_name`
    }

    // Mutator
    public function setRecipientNameAttribute($value)
    {
        $this->attributes['recipient_name'] = $value ?: 'Unknown'; // Gán giá trị hoặc 'Unknown' nếu null
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id', 'id');
    }
}
