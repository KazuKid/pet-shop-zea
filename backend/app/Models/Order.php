<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'shipping_method',
        'payment_method',
        'notes',
        'subtotal',
        'shipping_cost',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'      => 'integer',
            'shipping_cost' => 'integer',
            'total'         => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
