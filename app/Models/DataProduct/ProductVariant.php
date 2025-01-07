<?php

namespace App\Models\DataProduct;

use App\Models\BaseModel;

class ProductVariant extends BaseModel
{
    protected $table = 'product_variants';
    protected $guarded = ['id'];
}
