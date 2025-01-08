<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataProduct\Product;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category', 'brand'])->whereIn('status', ['tersedia', 'tidak_tersedia']);
    
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
    
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
    
        if ($request->search) {
            $query->where('product_name', 'ilike', '%' . $request->search . '%');
        }
    
        $products = $query->paginate(10);

        return view('landing.index', compact('products'));
    }
}
