<?php

namespace App\Http\Controllers;

use App\Models\Produk; 
use App\Models\RencanaPanen; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StokController extends Controller
{
    /**
     * Menampilkan daftar stok komoditas (index).
     */
    public function index()
    {
        // Mengambil SEMUA produk dari tabel 'produks'
        $produks = Produk::withSum(['rencanaPanens' => function ($query) {
            // Hanya hitung yang berstatus 'approved'
            $query->where('status', 'approved'); 
        }], 'estimasi_hasil_panen')
        // Menghitung rata-rata harga jual dari RencanaPanen yang disetujui
        ->withAvg(['rencanaPanens' => function ($query) {
             $query->where('status', 'approved');
        }], 'harga_jual')
        ->get();
        
        // Filter koleksi DIHILANGKAN agar semua produk ditampilkan di tabel index
        
        return view('stok.index', compact('produks'));
    }

    /**
     * [METHOD YANG HILANG] Menampilkan form untuk mengedit stok komoditas tertentu.
     */
    public function edit($id)
    {
        // Mengambil produk dan menghitung data agregatnya untuk ditampilkan di form
        $produk = Produk::withSum(['rencanaPanens' => function ($query) {
            // Menggunakan nama kolom yang benar: estimasi_waktu_panen
            $query->where('status', 'approved');
        }], 'estimasi_hasil_panen')
        ->withAvg(['rencanaPanens' => function ($query) {
            $query->where('status', 'approved');
        }], 'harga_jual')
        ->findOrFail($id);

        // Ambil nilai stok saat ini dari hasil agregasi
        $produk->current_stock = $produk->rencana_panens_sum_estimasi_hasil_panen ?? 0;
        $produk->rata_rata_harga = $produk->rencana_panens_avg_harga_jual ?? 0;


        return view('stok.edit', compact('produk'));
    }

    /**
     * Memproses dan menyimpan perubahan stok yang diajukan admin.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'new_stock' => 'required|numeric|min:0',
            'harga' => 'nullable|numeric|min:0',
        ]);

        $newStock = (float) $request->input('new_stock');
        $newHarga = $request->input('harga');
        
        $produk = Produk::findOrFail($id);

        // 2. Logic Update Stok
        DB::transaction(function () use ($produk, $newStock, $newHarga) {
            
            // Hapus semua rencana panen yang approved, agar kita bisa memasukkan nilai agregat baru
            RencanaPanen::where('produk_id', $produk->id)
                ->where('status', 'approved')
                ->delete();

            // Buat entri baru yang merepresentasikan stok total yang disesuaikan Admin
            RencanaPanen::create([
                'produk_id' => $produk->id,
                'petani_id' => auth()->id(), 
                'estimasi_hasil_panen' => $newStock,
                'status' => 'approved',
                'harga_jual' => $newHarga ?? 0,
                
                // Menggunakan estimasi_waktu_panen (sesuai DB)
                'estimasi_waktu_panen' => now(), 
            ]);

        });

        return redirect()->route('stok')->with('success', 'Stok komoditas ' . $produk->nama . ' berhasil diperbarui menjadi ' . $newStock . ' Kg.');
    }
    
    // Metode destroy
    public function destroy()
    {
        // ... (Implementasi delete stok jika diperlukan)
    }
}