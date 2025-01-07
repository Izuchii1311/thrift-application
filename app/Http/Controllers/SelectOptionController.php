<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduct\Brand;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\DataProduct\Category;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;

class SelectOptionController extends Controller
{
    /**
     * Get a collection of role information with selected attributes.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::select('id', 'role_name', 'display_name', 'description', 'is_active', 'type_role')
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
}
