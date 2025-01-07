<?php

namespace App\Models\DataProduct;

use App\Models\BaseModel;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends BaseModel
{
    use Sluggable;

    protected $table = 'categories';
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
                'source' => 'category_name'
            ]
        ];
    }
}
