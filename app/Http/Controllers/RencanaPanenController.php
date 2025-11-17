<?php

namespace App\Http\Controllers;
use App\Models\RencanaPanen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RencanaPanenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->estimasi_hasil);
        // 1. Validasi input dari form
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'estimasi_hasil' => 'required|numeric|min:0', // Menggunakan nama dari view
            'estimasi_waktu' => 'required|date', // Menggunakan nama dari view
        ]);

        // 2. Dapatkan data petani yang sedang login
        $petani = Auth::user()->petani;
        
        // 3. Simpan data ke tabel rencana_panens
        RencanaPanen::create([
            'petani_id' => $petani->id,
            'produk_id' => $request->produk_id,
            'estimasi_hasil_panen' => $request->estimasi_hasil,
            'estimasi_waktu_panen' => $request->estimasi_waktu,
            'status' => 'pending', // Status awal
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return back()->with('success', 'Rencana panen berhasil dilaporkan.');
    }
}