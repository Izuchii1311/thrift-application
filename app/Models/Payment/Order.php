<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\DataProduct\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
