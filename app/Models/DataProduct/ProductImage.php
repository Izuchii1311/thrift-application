<?php

namespace App\Models\DataProduct;

use App\Models\BaseModel;
use App\Models\DataProduct\Product;

class ProductImage extends BaseModel
{
    protected $table = 'product_images';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
