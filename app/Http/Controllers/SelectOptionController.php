<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduct\Brand;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\DataProduct\Category;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;
use App\Models\Masterdata\Lokasi\Kota;
use App\Models\Masterdata\Lokasi\Provinsi;
use App\Models\Masterdata\Lokasi\Kecamatan;
use App\Models\Masterdata\Lokasi\Kelurahan;

class SelectOptionController extends Controller
{
    /**
     * Get a collection of role information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::select('id', 'role_name', 'display_name', 'description', 'type_role')
                ->where('is_active', true)
                ->get();

        return $this->api_response_success('Data berhasil didapatkan.', $roles->toArray());
    }

    /**
     * Get a collection of menus information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getMenus(): JsonResponse
    {
        $menus = Menu::select('id', 'menu_name', 'path', 'key', 'menu_icon', 'parent_id', 'ordering')
                ->orderBy('ordering', 'asc')
                ->get();

        return $this->api_response_success('Data berhasil didapatkan.', $menus->toArray());
    }

    /**
     * Get a collection of menus where path not null
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getMainMenus(): JsonResponse
    {
        $menus = Menu::select('id', 'menu_name', 'path', 'key', 'menu_icon', 'parent_id', 'ordering')
                ->where('path', '!=', null)
                ->where('path', '!=', '')
                ->orderBy('ordering', 'asc')
                ->get();

        return $this->api_response_success('Data berhasil didapatkan.', $menus->toArray());
    }

    /**
     * Get a collection of categories information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $categories = Category::select('id', 'category_name', 'description')
                ->orderBy('category_name', 'asc')
                ->get();

        return $this->api_response_success('Data berhasil didapatkan.', $categories->toArray());
    }

    /**
     * Get a collection of brands information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getBrands(): JsonResponse
    {
        $brands = Brand::select('id', 'brand_name')
                ->orderBy('brand_name', 'asc')
                ->get();

        return $this->api_response_success('Data berhasil didapatkan.', $brands->toArray());
    }

    /**
     * Get a collection of masterdata_provinsi information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getProvinsi(Request $request)
    {
        $data = Provinsi::where('kode_provinsi', $request->search)
            ->orWhere('nama_provinsi', 'ilike', '%' . $request->search . '%')
            ->select('kode_provinsi', 'nama_provinsi')
            ->orderBy('nama_provinsi', 'asc')
            ->get();

        return $this->api_response_success('OK', $data->toArray());
    }

    /**
     * Get a collection of masterdata_kota information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getKota(Request $request)
    {
        $query = Kota::query();

        if ($request->input('kode_provinsi')) {
            $query->where('kode_provinsi', $request->kode_provinsi);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kota',      'ilike', '%' . $request->search . '%')
                    ->orWhere('kode_kota',      'ilike', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_kota', 'asc')
            ->select('kode_kota', 'nama_kota', 'kode_provinsi')
            ->get();

        return $this->api_response_success('OK', $data->toArray());
    }

    /**
     * Get a collection of masterdata_kecamatan information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getKecamatan(Request $request)
    {
        $query = Kecamatan::where('kode_kota', $request->kode_kota);

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kecamatan', 'ilike', '%' . $request->search . '%')
                    ->orWhere('kode_kecamatan', 'ilike', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_kecamatan', 'asc')
            ->select('kode_kecamatan', 'nama_kecamatan', 'kode_kota')
            ->get();

        return $this->api_response_success('OK', $data->toArray());
    }

    /**
     * Get a collection of masterdata_kelurahan information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getKelurahan(Request $request)
    {
        $query = Kelurahan::where('kode_kecamatan', $request->kode_kecamatan);

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kelurahan', 'ilike', '%' . $request->search . '%')
                    ->orWhere('kode_kelurahan', 'ilike', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_kelurahan', 'asc')
            ->select('kode_kelurahan', 'nama_kelurahan', 'kode_kecamatan')
            ->get();

        return $this->api_response_success('OK', $data->toArray());
    }


}
