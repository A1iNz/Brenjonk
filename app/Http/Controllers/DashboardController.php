<?php

namespace App\Http\Controllers;

use App\Models\Produk;
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

        if ($user->role === 'admin') {
            // Admin akan melihat konten dari yield('content') di app.blade.php
            return view('layouts.app');
        }

        if ($user->role === 'petani') {
            $produks = Produk::orderBy('nama')->get();
            return view('dashboard', compact('produks'));
        }

        return view('dashboard');
    }
}