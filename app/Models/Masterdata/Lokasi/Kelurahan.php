<?php

namespace App\Models\Masterdata\Lokasi;

use App\Models\BaseModel;

class Kelurahan extends BaseModel
{
    protected $table = 'masterdata_kelurahan';
    protected $primaryKey = 'kode_kelurahan';
    public $incrementing = false;

}
