<?php

namespace App\Models\Masterdata\Lokasi;

use App\Models\BaseModel;

class Kecamatan extends BaseModel
{
    protected $table = 'masterdata_kecamatan';
    protected $primaryKey = 'kode_kecamatan';
    public $incrementing = false;

}
