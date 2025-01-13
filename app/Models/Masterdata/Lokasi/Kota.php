<?php

namespace App\Models\Masterdata\Lokasi;

use App\Models\BaseModel;

class Kota extends BaseModel
{
    protected $table = 'masterdata_kota';
    protected $primaryKey = 'kode_kota';
    public $incrementing = false;

}
