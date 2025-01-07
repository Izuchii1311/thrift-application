<?php

namespace App\Http\Controllers\Masterdata\Lokasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Masterdata\Lokasi\Provinsi;
use Yajra\DataTables\Facades\DataTables;

class ProvinsiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.masterdata.lokasi.provinsi.index');
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Provinsi::select(
                'id',
                'nama_provinsi',
            );

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('id',               'ilike', '%' . $request->search . '%')
                        ->orWhere('nama_provinsi',    'ilike', '%' . $request->search . '%');
                });
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->toArray();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
