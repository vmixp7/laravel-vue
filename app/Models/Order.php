<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'order_number',
        'user_id',
        'product_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(
            ['name' => 'Guest Author'],
        );
    }
}
