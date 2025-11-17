<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::latest()->get(); // Mengambil semua data produk, diurutkan dari yang terbaru
        return view('produk.index', compact('produks'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:produks,kode',
        ]);
        
        Produk::create($request->all());

        return redirect()->route('produk')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:produks,kode,' . $produk->id,
        ]);

        $produk->update($request->all());

        return redirect()->route('produk')
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        try {
            $produk->delete();
            return redirect()->route('produk')
                             ->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('produk')
                             ->with('error', 'Gagal menghapus produk. Pastikan tidak ada data terkait.');
        }
    }
}
