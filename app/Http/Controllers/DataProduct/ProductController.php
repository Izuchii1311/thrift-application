<?php

namespace App\Http\Controllers\DataProduct;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DataProduct\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use App\Models\DataProduct\ProductImage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\DataProduct\ProductVariant;

class ProductController extends BaseController
{
    public function index()
    {
        $menu_access = $this->getMenuPermissionsByKey('product');
        return view('dashboard.data-product.product.index', compact('menu_access'));
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Product::select(
                'products.id',
                'products.category_id',
                'products.brand_id',
                'products.product_name',
                'products.slug',
                'products.description',
                'products.base_price',
                'products.total_stock',
                'products.status',
            
                'brands.brand_name',
                'categories.category_name'
            )
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id');
            
            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('products.product_name',      'ilike', '%' . $request->search . '%')
                          ->orWhere('categories.category_name', 'ilike', '%' . $request->search . '%')
                          ->orWhere('brands.brand_name',        'ilike', '%' . $request->search . '%');
                });
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('product_info', function ($row) {
                $product_image_thumbnail = ProductImage::where('product_id', $row->id)
                    ->where('is_primary', true)
                    ->first();
            
                $thumbnail_url = $product_image_thumbnail
                    ? asset('storage/' . $product_image_thumbnail->image_url)
                    : asset('assets/dashboard/media/no_image.jpg');
            
                    return '<div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label" style="background-image:url(\'' . $thumbnail_url . '\'); background-size: cover; background-position: center;" data-bs-toggle="modal" 
                            data-bs-target="#product_detail" onclick="loadDetailData(\'' . $row->slug . '\')"></span>
                        </div>
                        <div>
                            <a class="text-gray-800 text-hover-primary fs-5 fw-bold" data-bs-toggle="modal" 
                            data-bs-target="#product_detail" onclick="loadDetailData(\'' . $row->slug . '\')">' . $row->product_name . '</a>
                        </div>
                    </div>';
            })
            ->addColumn('base_price', function ($row) {
                return 'Rp. ' . $row->base_price;
            })
            ->addColumn('total_stock', function ($row) {
                $badgeClass = 'badge-secondary';
            
                if ($row->total_stock == 0) {
                    $badgeClass = 'badge-danger';
                } elseif ($row->total_stock < 50) {
                    $badgeClass = 'badge-warning';
                } elseif ($row->total_stock >= 50) {
                    $badgeClass = 'badge-primary';
                }
            
                return '<span class="badge ' . $badgeClass . '">' . $row->total_stock . '</span>';
            })
            ->addColumn('status', function ($row) {
                $formattedStatus = ucwords(str_replace('_', ' ', $row->status));
                
                $badgeClass = match ($row->status) {
                    'tersedia'          => 'badge-success',
                    'tidak_tersedia'    => 'badge-danger',
                    'proses'            => 'badge-warning',
                    'menunggu_validasi' => 'badge-info',
                    'tidak_layak'       => 'badge-secondary',
                    'draft'             => 'badge-primary',
                    default             => 'badge-secondary',
                };
            
                return '<span class="badge ' . $badgeClass . '">' . $formattedStatus . '</span>';
            })
            ->addColumn('action', function ($row) {
                $menu_access = $this->getMenuPermissionsByKey('product');

                $can_update = $menu_access['can_update'] ?? false;
                $can_delete = $menu_access['can_delete'] ?? false;

                return $this->renderActions($row, $can_update, $can_delete, $row->slug);
            })
            ->rawColumns(['base_price', 'total_stock', 'status', 'product_info', 'action'])
            ->toArray();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function create()
    {
        $menu_access = $this->getMenuPermissionsByKey('product');
        return view('dashboard.data-product.product.create', compact('menu_access'));
    }    

    public function store(Request $request)
    {
        try {
            $role = Auth::user()->roles->firstWhere('pivot.is_active', true)->role_name;

            $statusRules = match ($role) {
                'Opname' => 'required|in:draft,proses,menunggu_validasi,tidak_layak',
                'Admin' => 'required|in:tersedia,tidak_tersedia',
                default => 'required|in:draft,proses,menunggu_validasi,tidak_layak,tersedia,tidak_tersedia',
            };

            $rules = [
                'category_id'           => 'required|exists:categories,id',
                'brand_id'              => 'nullable|exists:brands,id',
                'product_name'          => 'required|string|max:50',
                'status'                => $statusRules,
                'description'           => 'required|string',
                'base_price'            => 'required|numeric',
                'total_stock'           => 'required|integer|min:1',
                'product_images.*'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'type_variant'          => 'array|nullable',
                'type_variant.*'        => 'nullable|string|in:warna,ukuran',
                'size_variant'          => 'array|nullable',
                'size_variant.*'        => 'nullable|string|max:50',
                'color_variant'         => 'array|nullable',
                'color_variant.*'       => 'nullable|string|max:50',
                'stock_quantity_size'   => 'array|nullable',
                'stock_quantity_size.*' => 'nullable|integer|min:0',
                'stock_quantity_color'  => 'array|nullable',
                'stock_quantity_color.*'=> 'nullable|integer|min:0',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return $this->api_response_validator('Periksa kembali data yang Anda isi!', [], $validator->errors()->toArray(), 422);
            }
    
            $validated              = $validator->validate();
            $totalStock             = $validated['total_stock'];
            $typeVariant            = $validated['type_variant'] ?? [];
            $stockQuantitiesSize    = $validated['stock_quantity_size'] ?? [];
            $stockQuantitiesColor   = $validated['stock_quantity_color'] ?? [];
    
            if (!empty($typeVariant)) {
                $isColorVariant = in_array('warna', $typeVariant);
                $isSizeVariant = in_array('ukuran', $typeVariant);

                if ($isColorVariant && array_sum($stockQuantitiesColor) > $totalStock) {
                    return $this->api_response_validator( 'Jumlah total stok warna tidak boleh melebihi total stok produk!', [], ['stock_quantity_color' => ['Jumlah total stok warna tidak boleh melebihi total stok produk']], 422 );
                }
    
                if ($isSizeVariant && array_sum($stockQuantitiesSize) > $totalStock) {
                    return $this->api_response_validator( 'Jumlah total stok ukuran tidak boleh melebihi total stok produk!', [], ['stock_quantity_size' => ['Jumlah total stok ukuran tidak boleh melebihi total stok produk']], 422 );
                }
            }
    
            DB::beginTransaction();
            $product = Product::create($validated);
    
            $uploadedImages = [];
            // Store product images to database
            foreach ($request->file('product_images', []) as $index => $image) {
                $path = $this->storeFile($image, 'product_images');
                $uploadedImages[] = $path;
    
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $path,
                    'is_primary' => $index === 0,
                ]);
            }
    
            // Store product variants to database
            foreach ($typeVariant as $key => $type) {
                $size   = $type === 'ukuran'    ? $validated['size_variant'][$key]  ?? null : null;
                $color  = $type === 'warna'     ? $validated['color_variant'][$key] ?? null : null;
                $stock  = $type === 'ukuran'
                    ? $stockQuantitiesSize[$key] ?? 0
                    : $stockQuantitiesColor[$key] ?? 0;

                // Skip saving variant if stock is null or 0
                if ($stock === null || $stock == 0) {
                    continue;
                }

                ProductVariant::create([
                    'product_id'     => $product->id,
                    'type_variant'   => $type,
                    'size'           => $size,
                    'color'          => $color,
                    'stock_quantity' => $stock,
                ]);
            }

    
            DB::commit();
            return $this->api_response_success('Berhasil menambahkan data Produk baru.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            foreach ($uploadedImages as $path) {
                if (isset($path) && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                    $this->deleteEmptyDirectory(dirname($path));
                }
            }
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($slug)
    {
        try {
            $product = Product::with(['images', 'variants', 'category', 'brand'])
            ->where('slug', $slug)
            ->first();

        return $product
            ? $this->api_response_success('Berhasil menampilkan data Produk.', $product->toArray())
            : $this->api_response_error('Data Produk tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function edit($slug)
    {
        $menu_access = $this->getMenuPermissionsByKey('product');
        return view('dashboard.data-product.product.edit', compact('menu_access', 'slug'));
    }
    
    public function update(Request $request, $slug)
    {
        try {
            $role = Auth::user()->roles->firstWhere('pivot.is_active', true)->role_name;

            $statusRules = match ($role) {
                'Opname' => 'required|in:draft,proses,menunggu_validasi,tidak_layak',
                'Admin' => 'required|in:tersedia,tidak_tersedia',
                default => 'required|in:draft,proses,menunggu_validasi,tidak_layak,tersedia,tidak_tersedia',
            };

            $rules = [
                'category_id'      => 'required|exists:categories,id',
                'brand_id'         => 'nullable|exists:brands,id',
                'product_name'     => 'required|string|max:50',
                'status'           => $statusRules,
                'description'      => 'required|string',
                'base_price'       => 'required|numeric',
                'total_stock'      => 'required|numeric|min:1',
                'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'type_variant'     => 'array|nullable',
                'type_variant.*'   => 'nullable|string|in:warna,ukuran',
                'size_variant'     => 'array|nullable',
                'size_variant.*'   => 'nullable|string|max:50',
                'color_variant'    => 'array|nullable',
                'color_variant.*'  => 'nullable|string|max:50',
                'stock_quantity_size'   => 'array|nullable',
                'stock_quantity_size.*' => 'nullable|integer|min:0',
                'stock_quantity_color'  => 'array|nullable',
                'stock_quantity_color.*'=> 'nullable|integer|min:0',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return $this->api_response_validator('Periksa kembali data yang Anda isi!', [], $validator->errors()->toArray(), 422);
            }
    
            $validated              = $validator->validate();
            $totalStock             = $validated['total_stock'];
            $typeVariant            = $validated['type_variant'] ?? [];
            $stockQuantitiesSize    = $validated['stock_quantity_size'] ?? [];
            $stockQuantitiesColor   = $validated['stock_quantity_color'] ?? [];
    
            Log::info('Cek variant', [
                'totalStock' => $totalStock,
                'typeVariant' => $typeVariant,
                'stockQuantitiesSize' => $stockQuantitiesSize,
                'stockQuantitiesColor' => $stockQuantitiesColor,
            ]);

            if (!empty($typeVariant)) {
                $isColorVariant     = in_array('warna', $typeVariant);
                $isSizeVariant      = in_array('ukuran', $typeVariant);

                if ($isColorVariant && array_sum($stockQuantitiesColor) > $totalStock) {
                    return $this->api_response_validator( 'Jumlah total stok warna tidak boleh melebihi total stok produk!', [], ['stock_quantity_color' => ['Jumlah total stok warna tidak boleh melebihi total stok produk']], 422 );
                }
    
                if ($isSizeVariant && array_sum($stockQuantitiesSize) > $totalStock) {
                    return $this->api_response_validator( 'Jumlah total stok ukuran tidak boleh melebihi total stok produk!', [], ['stock_quantity_size' => ['Jumlah total stok ukuran tidak boleh melebihi total stok produk']], 422 );
                }
            }
    
            $uploadedImages = [];
            $oldImagesToDelete = [];

            DB::beginTransaction();
            $product = Product::where('slug', $slug)->firstOrFail();
            $product->update($validated);
    
            // Handle File
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $index => $image) {
                    $path = $this->storeFile($image, 'product_images');
                    $uploadedImages[] = $path;
    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
    
                $oldImagesToDelete = ProductImage::where('product_id', $product->id)->pluck('image_url')->toArray();
                ProductImage::where('product_id', $product->id)->delete();
            }
    
            // Update Product Variant
            foreach ($typeVariant as $key => $type) {
                $size   = $type === 'ukuran'    ? $validated['size_variant'][$key]  ?? null : null;
                $color  = $type === 'warna'     ? $validated['color_variant'][$key] ?? null : null;
                $stock  = $type === 'ukuran'
                    ? $stockQuantitiesSize[$key] ?? 0
                    : $stockQuantitiesColor[$key] ?? 0;

                // Skip if stock is null or 0
                if ($stock === null || $stock == 0) {
                    ProductVariant::where('product_id', $product->id)
                        ->where('type_variant', $type)
                        ->where('size', $size)
                        ->where('color', $color)
                        ->delete();
                    continue;
                }

                // Update or create product variant
                $variant = ProductVariant::where('product_id', $product->id)
                                        ->where('type_variant', $type)
                                        ->where('size', $size)
                                        ->where('color', $color)
                                        ->first();

                if ($variant) {
                    $variant->update(['stock_quantity' => $stock]);
                } else {
                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'type_variant'   => $type,
                        'size'           => $size,
                        'color'          => $color,
                        'stock_quantity' => $stock,
                    ]);
                }
            }
    
            DB::commit();
    
            foreach ($oldImagesToDelete as $oldImage) {
                if (isset($oldImage) && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                    $this->deleteEmptyDirectory(dirname($oldImage));
                }
            }
    
            return $this->api_response_success('Berhasil memperbarui data Produk.', $product->fresh()->toArray());
        } catch (\Throwable $th) {
            DB::rollBack();
            foreach ($uploadedImages as $uploadedImage) {
                if (isset($uploadedImage) && Storage::disk('public')->exists($uploadedImage)) {
                    Storage::disk('public')->delete($uploadedImage);
                    $this->deleteEmptyDirectory(dirname($uploadedImage));
                }
            }
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($slug)
    {
        try {
            $product = Product::where('slug', $slug)->first();

            if (!$product) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            DB::beginTransaction();
            $product->images()->delete();
            $product->variants()->delete();
            $product->delete();
            $product->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Produk.', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}


