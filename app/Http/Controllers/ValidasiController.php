<?php

namespace App\Http\Controllers;

use App\Models\RencanaPanen;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    public function index()
    {
        $rencanaPanens = RencanaPanen::with(['petani.user', 'produk'])->where('status', 'pending')->get();
        return view('validasi.index', compact('rencanaPanens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,reject',
        ]);

        $rencanaPanen = RencanaPanen::findOrFail($id);
        $rencanaPanen->status = $request->status;
        $rencanaPanen->save();

        return redirect()->route('validasi')->with('success', 'Status laporan panen berhasil diperbarui.');
    }
}
