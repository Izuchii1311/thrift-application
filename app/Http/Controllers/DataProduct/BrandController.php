<?php

namespace App\Http\Controllers\DataProduct;

use Illuminate\Http\Request;
use App\Models\DataProduct\Brand;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\DataProduct\BrandRequest;

class BrandController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu_access = $this->getMenuPermissionsByKey('brand');
        return view('dashboard.data-product.brand.index', compact('menu_access'));
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Brand::select(
                'brands.brand_name',
                'brands.slug',
            );

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('brands.category_name','ilike', '%' . $request->search . '%');
                });
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $menu_access = $this->getMenuPermissionsByKey('category');

                $can_update = $menu_access['can_update'] ?? false;
                $can_delete = $menu_access['can_delete'] ?? false;

                return $this->renderActions($row, $can_update, $can_delete, $row->slug);
            })
            ->rawColumns(['action'])
            ->toArray();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function store(BrandRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();
            Brand::create($validated);
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Brand baru.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($slug)
    {
        try {
            $brand  = Brand::select('brand_name')
                        ->where('slug', $slug)
                        ->first();

            return $brand
                ? $this->api_response_success('Berhasil menampilkan data Brand.', $brand->toArray())
                : $this->api_response_error('Data Brand tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(BrandRequest $request, $slug)
    {
        try {
            $brand = Brand::where('slug', $slug)->first();

            if (!$brand) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            $validated = $request->validated();

            DB::beginTransaction();
            $brand->update($validated);
            DB::commit();

            return $this->api_response_success('Berhasil memperbarui data Brand.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($slug)
    {
        try {
            $brand = Brand::where('slug', $slug)->first();

            if (!$brand) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            DB::beginTransaction();
            $brand->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Brand.', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
