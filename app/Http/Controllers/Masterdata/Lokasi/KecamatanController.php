<?php

namespace App\Http\Controllers\Masterdata\Lokasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Masterdata\Lokasi\Kecamatan;

class KecamatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.masterdata.lokasi.kecamatan.index');
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Kecamatan::select(
                'masterdata_kecamatan.id',
                'masterdata_kecamatan.kode_kota',
                'masterdata_kecamatan.nama_kecamatan',
                'masterdata_kota.id as kode_kota',
                'masterdata_kota.nama_kota'
            )
            ->join('masterdata_kota', 'masterdata_kecamatan.kode_kota', '=', 'masterdata_kota.id');
    
            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('masterdata_kecamatan.id',                'ilike', '%' . $request->search . '%')
                          ->orWhere('masterdata_kota.nama_kota',            'ilike', '%' . $request->search . '%')
                          ->orWhere('masterdata_kecamatan.nama_kecamatan',  'ilike', '%' . $request->search . '%');
                });
            }
    
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('kota_info', function ($row) {
                return $row->kode_kota . ' - ' . $row->nama_kota;
            })
            ->rawColumns(['kota_info'])
            ->toJson();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
