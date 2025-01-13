<?php

namespace App\Models\Masterdata\Lokasi;

use App\Models\BaseModel;

class Provinsi extends BaseModel
{
    protected $table = 'masterdata_provinsi';
    protected $primaryKey = 'kode_provinsi';
    public $incrementing = false;

}
