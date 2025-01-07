<?php

namespace App\Http\Controllers\Masterdata\Lokasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Masterdata\Lokasi\Kota;
use Yajra\DataTables\Facades\DataTables;

class KotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.masterdata.lokasi.kota.index');
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Kota::select(
                'masterdata_kota.id',
                'masterdata_kota.kode_provinsi',
                'masterdata_kota.nama_kota',
                'masterdata_provinsi.id as kode_provinsi',
                'masterdata_provinsi.nama_provinsi'
            )
            ->join('masterdata_provinsi', 'masterdata_kota.kode_provinsi', '=', 'masterdata_provinsi.id');
    
            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('masterdata_kota.id',                     'ilike', '%' . $request->search . '%')
                          ->orWhere('masterdata_provinsi.nama_provinsi',    'ilike', '%' . $request->search . '%')
                          ->orWhere('masterdata_kota.nama_kota',            'ilike', '%' . $request->search . '%');
                });
            }
    
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('provinsi_info', function ($row) {
                return $row->kode_provinsi . ' - ' . $row->nama_provinsi;
            })
            ->rawColumns(['provinsi_info'])
            ->toJson();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
