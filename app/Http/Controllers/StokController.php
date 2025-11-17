<?php

namespace App\Http\Controllers;

use App\Models\RencanaPanen;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $produks = \App\Models\Produk::withSum(['rencanaPanens' => function ($query) {
            $query->where('status', 'approved')
                  ->where('estimasi_waktu_panen', '<=', now());
        }], 'estimasi_hasil_panen')->get();
        return view('stok.index', compact('produks'));
    }
}
