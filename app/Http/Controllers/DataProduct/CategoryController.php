<?php

namespace App\Http\Controllers\DataProduct;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataProduct\Category;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\DataProduct\CategoryRequest;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu_access = $this->getMenuPermissionsByKey('category');
        return view('dashboard.data-product.category.index', compact('menu_access'));
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Category::select(
                'categories.category_name',
                'categories.slug',
                'categories.description',
            );

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('categories.category_name','ilike', '%' . $request->search . '%')
                        ->orWhere('categories.description',  'ilike', '%' . $request->search . '%');
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

    public function store(CategoryRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();
            Category::create($validated);
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Kategori baru.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($slug)
    {
        try {
            $category = Category::select('category_name', 'description')
                        ->where('slug', $slug)
                        ->first();

            return $category
                ? $this->api_response_success('Berhasil menampilkan data Kategori.', $category->toArray())
                : $this->api_response_error('Data Kategori tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(CategoryRequest $request, $slug)
    {
        try {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            $validated = $request->validated();

            DB::beginTransaction();
            $category->update($validated);
            DB::commit();

            return $this->api_response_success('Berhasil memperbarui data Kategori.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($slug)
    {
        try {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            DB::beginTransaction();
            $category->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Kategori.', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
