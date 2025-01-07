<?php

namespace App\Http\Controllers\Masterdata\Lokasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Masterdata\Lokasi\Kelurahan;

class KelurahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.masterdata.lokasi.kelurahan.index');
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Kelurahan::select(
                'masterdata_kelurahan.id',
                'masterdata_kelurahan.kode_kecamatan',
                'masterdata_kelurahan.nama_kelurahan',
                'masterdata_kecamatan.id as kode_kecamatan',
                'masterdata_kecamatan.nama_kecamatan'
            )
            ->join('masterdata_kecamatan', 'masterdata_kelurahan.kode_kecamatan', '=', 'masterdata_kecamatan.id');
    
            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('masterdata_kelurahan.id',                'ilike', '%' . $request->search . '%')
                          ->orWhere('masterdata_kecamatan.nama_kecamatan',  'ilike', '%' . $request->search . '%')
                          ->orWhere('masterdata_kelurahan.nama_kelurahan',  'ilike', '%' . $request->search . '%');
                });
            }
    
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('kecamatan_info', function ($row) {
                return $row->kode_kecamatan . ' - ' . $row->nama_kecamatan;
            })
            ->rawColumns(['kecamatan_info'])
            ->toJson();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
