<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\RencanaPanen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     * Mengambil data untuk Dashboard, menyesuaikan berdasarkan role user.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        
        // Data Global untuk Statistik dan Form Lapor Panen
        $jumlahProdukTerdaftar = Produk::count(); 
        $produks = Produk::all(); // Untuk dropdown di form lapor

        if ($user->role === 'admin') {
            // Logika untuk Admin (Melihat Semua Data)
            $jumlahPending = RencanaPanen::where('status', 'pending')->count();
            $jumlahApproved = RencanaPanen::where('status', 'approved')->count();
            
            // Riwayat panen yang ditampilkan (Untuk Admin, bisa melihat semua panen)
            $panens = RencanaPanen::with('produk')->orderBy('created_at', 'desc')->limit(10)->get();

        } else {
            // Logika untuk Petani (Hanya melihat data miliknya)
            $jumlahPending = RencanaPanen::where('petani_id', $userId)->where('status', 'pending')->count();
            $jumlahApproved = RencanaPanen::where('petani_id', $userId)->where('status', 'approved')->count();

            // Riwayat panen petani yang sedang login
            $panens = RencanaPanen::where('petani_id', $userId)
                                ->with('produk')
                                ->orderBy('created_at', 'desc')
                                ->get();
        }

        return view('dashboard', compact(
            'jumlahProdukTerdaftar',
            'jumlahPending',
            'jumlahApproved',
            'produks',
            'panens'
        ));
    }
}