<?php

namespace App\Models\DataProduct;

use App\Models\BaseModel;
use App\Models\DataProduct\Brand;
use App\Models\DataProduct\Category;
use App\Models\DataProduct\ProductImage;
use App\Models\DataProduct\ProductVariant;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends BaseModel
{
    use Sluggable;

    protected $table = 'products';
    protected $guarded = ['id'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'product_name'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getBasePriceAttribute($value)
    {
        return number_format($value, 0, ',', '.');
    }
}
