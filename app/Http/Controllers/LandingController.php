<?php

namespace App\Http\Controllers;

use App\Models\User;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Payment\Order;
use Illuminate\Support\Facades\DB;
use App\Models\DataProduct\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use App\Models\Masterdata\Lokasi\Kota;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;

class LandingController extends BaseController
{
    public function __construct()
    {
        Configuration::setXenditKey("xnd_development_6hshzFU9oY5HhzK1CwH4mgRN4S4UVclAEVnehH4i9Q1WMiGGH01ECyZs7dXIZbn");
    }

    # Landing View
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category', 'brand'])
            ->whereIn('status', ['tersedia', 'tidak_tersedia'])
            ->when($request->category_id, fn($query) => $query->where('category_id', $request->category_id))
            ->when($request->brand_id, fn($query) => $query->where('brand_id', $request->brand_id))
            ->when($request->search, fn($query) => $query->where('product_name', 'ilike', '%' . $request->search . '%'));

        $products = $query->paginate(10);

        return view('landing.index', compact('products'));
    }

    # Profile View
    public function profileView()
    {
        $rule_role = Role::where('role_name', 'Customer')->orWhere('type_role', 'customer')->first();
        $user_info = User::userRoleActiveInfo()['role_active_as'];

        if ($user_info !== 'customer' && $user_info !== $rule_role->role_name) {
            return redirect()->route('dashboard.index');
        }

        $encryptedId = Crypt::encrypt(Auth::id());

        $user = User::join('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('masterdata_provinsi', 'user_address.kode_provinsi', '=', 'masterdata_provinsi.kode_provinsi')
            ->leftJoin('masterdata_kota', 'user_address.kode_kota', '=', 'masterdata_kota.kode_kota')
            ->leftJoin('masterdata_kecamatan', 'user_address.kode_kecamatan', '=', 'masterdata_kecamatan.kode_kecamatan')
            ->leftJoin('masterdata_kelurahan', 'user_address.kode_kelurahan', '=', 'masterdata_kelurahan.kode_kelurahan')
            ->select(
                'users.*',
                'user_address.*',
                'masterdata_provinsi.nama_provinsi',
                'masterdata_kota.nama_kota',
                'masterdata_kecamatan.nama_kecamatan',
                'masterdata_kelurahan.nama_kelurahan'
            )
            ->where('users.id', Auth::id())
            ->first();

        return view('landing.profile', compact('encryptedId', 'user'));
    }

    # Update Profile & Create or Update Address
    public function updateProfileAddress(AddressRequest $request, $encryptedId)
    {
        try {
            $user_id    = Crypt::decrypt($encryptedId);
            $user       = User::where('id', $user_id)->first();
            if (!$user) return $this->api_response_error('Data tidak ditemukan.');

            $validated = $request->validated();
            $oldFilePath = $user->profile_picture;
            $newFilePath = null;

            DB::beginTransaction();
            if ($request->hasFile('profile_picture')) {
                $newFilePath = $this->storeFile($request->file('profile_picture'), 'users/profile');
                if (!$newFilePath) throw new \Exception('Gagal menyimpan file.');
                $validated['profile_picture'] = $newFilePath;
            } else {
                if ($request->input('remove_photo') == 'true') {
                    if (Storage::disk('public')->exists($oldFilePath)) {
                        Storage::disk('public')->delete($oldFilePath);
                        $this->deleteEmptyDirectory(dirname($oldFilePath));
                    }
                    $validated['profile_picture'] = null;
                } else {
                    $validated['profile_picture'] = $oldFilePath;
                }
            }

            $user->update([
                'username'          => $validated['username'],
                'name'              => $validated['name'],
                'profile_picture'   => $validated['profile_picture'],
            ]);

            $addressData = [
                'user_id'           => $user->id,
                'nama'              => $user->name,
                'nomor_handphone'   => $validated['nomor_handphone'],
                'kode_provinsi'     => $validated['kode_provinsi'],
                'kode_kota'         => $validated['kode_kota'],
                'kode_kecamatan'    => $validated['kode_kecamatan'],
                'kode_kelurahan'    => $validated['kode_kelurahan'],
                'kode_pos'          => $validated['kode_pos'],
                'alamat_lengkap'    => $validated['alamat_lengkap'],
                'catatan'           => $validated['catatan'],
            ];

            UserAddress::updateOrCreate( ['user_id' => $user->id], $addressData );
            DB::commit();

            if ($newFilePath && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
                $this->deleteEmptyDirectory(dirname($newFilePath));
            }

            return $this->api_response_success('Berhasil memperbarui data.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            if (isset($newFilePath) && Storage::disk('public')->exists($newFilePath)) {
                Storage::disk('public')->delete($newFilePath);
                $this->deleteEmptyDirectory(dirname($newFilePath));
            }
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    # Get Data
    public function detailJson($encryptedId)
    {
        try {
            $user_id = Crypt::decrypt($encryptedId);

            $user = User::join('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('masterdata_provinsi', 'user_address.kode_provinsi', '=', 'masterdata_provinsi.kode_provinsi')
            ->leftJoin('masterdata_kota', 'user_address.kode_kota', '=', 'masterdata_kota.kode_kota')
            ->leftJoin('masterdata_kecamatan', 'user_address.kode_kecamatan', '=', 'masterdata_kecamatan.kode_kecamatan')
            ->leftJoin('masterdata_kelurahan', 'user_address.kode_kelurahan', '=', 'masterdata_kelurahan.kode_kelurahan')
            ->select(
                'users.*',
                'user_address.*',
                'masterdata_provinsi.nama_provinsi',
                'masterdata_kota.nama_kota',
                'masterdata_kecamatan.nama_kecamatan',
                'masterdata_kelurahan.nama_kelurahan'
            )
            ->where('users.id', $user_id)
            ->first();

            return $user
                ? $this->api_response_success('Berhasil menampilkan data.', $user->toArray())
                : $this->api_response_error('Data tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function payment(Request $request)
    {
        try {
            $rules = [
                'product_slug'  => 'required|exists:products,slug',
                'amount'        => 'required|numeric|min:1',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->api_response_validator( 'Periksa kembali data yang anda isi!', [], $validator->errors()->toArray(), 422 );
            }

            $validated = $validator->validate();

            $product = Product::where('slug', $validated['product_slug'])->first();
            if (!$product) return $this->api_response_error('Produk tidak ditemukan');

            $user = Auth::user();
            if (!$user) return redirect()->route('login_view');

            $user_address = UserAddress::where('user_id', $user->id)->first();
            if (!$user_address) return redirect()->route('profileView');

            $kota = Kota::where('kode_kota', $user_address->kode_kota)->first();
            $nama_kota = $kota->nama_kota ?? null;

            DB::beginTransaction();
            $uuid = (string) Str::uuid();

            $apiInstance = new InvoiceApi();
            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id'   => $uuid,
                'description'   => $product->product_name,
                'amount'        => $validated['amount'] * $product->final_price,
                'currency'      => 'IDR',
                'customer'      => [
                    'given_names'   => $user->name,
                    'surname'       => $user->username,
                    'email'         => $user->email,
                    'mobile_number' => $user_address->nomor_handphone,
                    'addresses'     => [
                        [
                            'city'          => $nama_kota,
                            'country'       => 'Indonesia',
                            'postal_code'   => $user_address->kode_pos,
                            'street_line1'  => $user_address->alamat_lengkap,
                        ]
                    ]
                ],
                'success_redirect_url' => route('landing-index'),
                'failure_redirect_url' => route('profileView'),
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            $order = new Order();
            $order->product_id      = $product->id;
            $order->user_id         = $user->id;
            $order->checkout_link   = $result['invoice_url'];
            $order->external_id     = $uuid;
            $order->amount          = $request->amount;
            $order->status          = 'PENDING';
            $order->save();

            // Redirect ke URL pembayaran Xendit
            
            DB::commit();
            return redirect($result['invoice_url']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->api_response_error($e->getMessage() . ' - ' . $e->getLine(), [], $e->getTrace());
            Log::error('Xendit Payment Error: ' . $e->getMessage());
            return response()->json(['error' => 'Pembayaran gagal. Silakan coba lagi nanti.'], 500);
        }
    }

    public function handleOrderCallback(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $apiInstance = new InvoiceApi();
            $result = $apiInstance->getInvoices(null, $id);
            $order = Order::where('external_id', $id)->firstOrFail();
    
            if ($order->status == 'settled') {
                return response()->json('Payment anda telah berhasil diproses.');
            }
    
            $order->status = $result[0]['status'];
            $order->save();
            DB::commit();
            return response()->json('Payment anda telah berhasil diproses. Success.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->api_response_error($e->getMessage() . ' - ' . $e->getLine(), [], $e->getTrace());
        }
    }


    // // Route::post('payment', 'payment')->name('payment');
    // public function payment(Request $request)
    // {
    //     $product = Product::find($request->id);
    //     $uuid = (string) Str::uuid();

    //     // Call Xendit
    //     $apiInstance = new Invoice();
    //     $createInvoiceRequest = new CreateInvoiceRequest([
    //         'external_id' => $uuid,
    //         'description' => "Testing",
    //         'amount'      => 10000,
    //         'currency'    => 'IDR',
    //         'customer'    => [
    //             'given_names'   => "John",
    //             'surname'       => "Doe",
    //             'email'         => "johndoe@example.com",
    //             'mobile_number' => "+6287774441111",
    //             'addresses'     => [
    //                 [
    //                     'city'          => "Jakarta Selatan",
    //                     'country'       => "Indonesia",
    //                     'postal_code'   => "12345",
    //                     'state'         => "Daerah Khusus Ibukota Jakarta",
    //                     'street_line1'  => "Jalan Makan",
    //                     'street_line2'  => "Kecamatan Kebayoran Baru"
    //                 ]
    //             ]
    //         ],
    //         "success_redirect_url"  => "https://www.youtube.com",
    //         "failure_redirect_url"  => "https://www.google.com",
    //     ]);

    //     try {
    //         $result = $apiInstance->createInvoice($createInvoiceRequest);
            // Insert to table order
            // $order = new Order();
            // $order->product_id = $product->id;
            // $order->checkout_link = $result['invoice_url'];
            // $order->external_id = $uuid;
            // $order->status = 'PENDING'
            // $order->save();

    //         return redirect($result['invoice_url']);
    //     } catch (\Xendit\XenditSdkException $e) {
    //         echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
    //         echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
    //     }
    // }

    // // Route::get('notification/{id}', 'notification')->name('notification');
    // // PENDING, PAID, EXPIRED
    // public function notification($id) {
    //     $apiInstance = new InvoiceApi();

    //     $result = $apiInstance->getInvoices(null, $id);

    //     // Get Data
    //     $order = Order::where('external_id', $id)->firstOrFail();

    //     if ($order->status == 'settled') {
    //         return response()->json('Payment anda telah berhasil diproses.');
    //     }

    //     // Update Status
    //     $order->status = $result[0]['status'];
    //     $order->save();

    //     return response()->json('Payment anda telah berhasil diproses. Success.');
    // }
}
