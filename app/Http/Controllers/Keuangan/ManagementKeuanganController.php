<?php

namespace App\Http\Controllers\Keuangan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Keuangan\ManagementKeuangan;
use App\Http\Requests\Keuangan\ManagementKeuanganRequest;

class ManagementKeuanganController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu_access    = $this->getMenuPermissionsByKey('management-keuangan');
        $currentMonth   = now()->format('F');
        $currentYear    = now()->year;
        $currentPeriode = $currentMonth . ' / ' . $currentYear;
    
        $keuanganBulanIni = ManagementKeuangan::where('periode', $currentPeriode)->first();
    
        return view('dashboard.keuangan.management-keuangan.index', compact('menu_access', 'keuanganBulanIni'));
    }
    

    public function indexJson(Request $request)
    {
        try {
            $data = ManagementKeuangan::select(
                'keuangan.id',
                'keuangan.modal_pemasukan',
                'keuangan.modal_pengeluaran',
                'keuangan.periode',
                'keuangan.catatan',
            );

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('keuangan.periode',            'ilike', '%' . $request->search . '%');
                });
            }

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $menu_access = $this->getMenuPermissionsByKey('management-keuangan');

                $can_update = $menu_access['can_update'] ?? false;
                $can_delete = $menu_access['can_delete'] ?? false;

                return $this->renderActions($row, $can_update, $can_delete);
            })
            ->rawColumns(['action'])
            ->toJson();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function store(ManagementKeuanganRequest $request)
    {
        try {
            $validated = $request->validated();
            $periode = $validated['periode'];

            DB::beginTransaction();
            $keuangan = ManagementKeuangan::where('periode', $periode)->first();

            if ($keuangan) {
                $keuangan->modal_pemasukan += $validated['modal_pemasukan'];
                $keuangan->modal_pengeluaran += $validated['modal_pengeluaran'] ?? 0;
                $keuangan->catatan = $validated['catatan'] ?? $keuangan->catatan;
                $keuangan->save();
            } else {
                ManagementKeuangan::create($validated);
            }
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Keuangan', $validated);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($encryptedId)
    {
        try {
            $keuangan_id    = Crypt::decrypt($encryptedId);
            $keuangan       = ManagementKeuangan::select('*')
            ->where('id', $keuangan_id)
            ->first();

            return $keuangan
                ? $this->api_response_success('Berhasil menampilkan data Keuangan.', $keuangan->toArray())
                : $this->api_response_error('Data Keuangan tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(ManagementKeuanganRequest $request, $encryptedId)
    {
        try {
            $keuangan_id  = Crypt::decrypt($encryptedId);
            $keuangan     = ManagementKeuangan::where('id', $keuangan_id)->first();

            if (!$keuangan) {
                return $this->api_response_error('Data tidak ditemukan');
            }

            $validated = $request->validated();

            DB::beginTransaction();
            $keuangan->update($validated);
            DB::commit();

            return $this->api_response_success('Berhasil memperbarui data Keuangan.', $validated);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $keuangan_id  = Crypt::decrypt($encryptedId);
            $keuangan     = ManagementKeuangan::where('id', $keuangan_id)->first();

            if (!$keuangan) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            DB::beginTransaction();
            $keuangan->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Keuangan.', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
