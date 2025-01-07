
Berikut adalah deskripsi dan gambaran kebutuhan aplikasi Anda, serta fitur dan menu yang perlu disiapkan:

# Deskripsi Aplikasi
Aplikasi ini adalah platform berbasis web untuk menjual baju thrift dengan model bisnis seperti e-commerce. Dalam aplikasi ini, terdapat tiga jenis pengguna dengan peran dan tugas yang berbeda: Opname, Admin, dan User (Customer). Setiap peran memiliki fitur dan akses yang dirancang khusus untuk mendukung proses bisnis dari pengelolaan stok hingga pembelian oleh pelanggan.


# Peran dan Hak Akses
1. Opname
Tugas Utama:
    - Mengelola data stok baju (CRUD).
    - Menentukan harga awal baju berdasarkan penilaian kondisi atau kategori.
    
Fitur yang Dibutuhkan:
    - Manajemen Stok Baju:
        - Input data baju (kode baju, kategori, deskripsi, harga awal, kondisi, foto).
        - Melihat daftar stok baju.
        - Mengedit data baju (jika ada kesalahan).
        - Menghapus baju dari stok (jika sudah tidak relevan).

    - Laporan Stok:
        - Melihat jumlah stok yang telah diinput, baik per kategori maupun total.

2. Admin
Tugas Utama:
    - Memilih baju yang layak dijual dari stok yang dibuat oleh Opname.
    - Menentukan harga jual final dengan menambahkan margin keuntungan.
    - Mengelola data transaksi dan laporan penjualan.

Fitur yang Dibutuhkan:
    - Validasi Stok:
        - Melihat daftar baju dari Opname.
        - Memilih baju yang layak dijual.
        - Menentukan margin keuntungan dan harga jual final.
        - Mengubah status baju menjadi "Tersedia untuk Dijual."
    - Manajemen Pesanan:
        - Melihat pesanan yang dibuat oleh pelanggan.
        - Memproses pesanan (konfirmasi, pembatalan, atau pengiriman).
    - Laporan Penjualan:
        - Melihat laporan penjualan berdasarkan periode (harian, bulanan, dll.).
        - Melihat keuntungan total berdasarkan margin.

3. User / Customer
Tugas Utama:
    - Melihat daftar baju yang dijual.
    - Membeli baju dan melakukan pembayaran.

Fitur yang Dibutuhkan:
    - Beranda / Katalog Produk:
        - Melihat daftar baju yang tersedia.
        - Filter berdasarkan kategori, harga, atau kondisi.
        - Menambah baju ke keranjang belanja.
    - Proses Pembelian:
        - Checkout dan mengisi alamat pengiriman.
        - Memilih metode pembayaran.
        - Melihat status pesanan.
    - Akun Pengguna:
        - Melihat riwayat pembelian.
        - Mengedit informasi akun.

# Saran Role Tambahan (Opsional)
Super Admin: Untuk memonitor dan mengelola seluruh aktivitas dalam aplikasi, termasuk pengaturan user, role, dan pengaturan sistem.

# Fitur / Menu Utama
Dashboard
Manajemen Stok                              (Untuk Opname dan Admin)
Katalog Produk                              (Untuk Customer)
Keranjang Belanja dan Checkout              (Untuk Customer)
Manajemen Pesanan                           (Untuk Admin dan Customer)
Laporan dan Statistik                       (Untuk Admin)
Laporan stok, penjualan, dan keuntungan.
Pengaturan Akun
