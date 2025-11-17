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
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        
        $produks = Produk::all();
        $panens = RencanaPanen::all();
        $jumlahProdukTerdaftar = $produks->count();
        $jumlahPending = $panens->where('status', 'pending')->count();
        $jumlahApproved = $panens->where('status', 'approved')->count();
        
        if ($user->role === 'admin') {
            // Admin akan melihat konten dari yield('content') di app.blade.php
            return view('layouts.app');
        }

        if ($user->role === 'petani') {
            $produks = Produk::orderBy('nama')->get();
            $panens = collect(); // Default to an empty collection

            // Pastikan user adalah petani dan memiliki relasi petani
            if ($user->role === 'petani' && $user->petani) {
                // Ambil data rencana panen milik petani yang sedang login
                // Urutkan berdasarkan yang terbaru dan eager load relasi produk
                $panens = RencanaPanen::where('petani_id', $user->petani->id)
                    ->with('produk')
                    ->latest('created_at')->get();
            }
            return view('dashboard', compact('produks', 'panens', 'jumlahProdukTerdaftar', 'jumlahPending', 'jumlahApproved'));
        }
        return view('dashboard');
    }
}