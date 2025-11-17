<?php

namespace App\Http\Controllers;

use App\Models\RencanaPanen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $panens = collect(); // Default to an empty collection

        if ($user->role === 'petani' && $user->petani) {
            $panens = RencanaPanen::where('petani_id', $user->petani->id)
                ->with('produk')
                ->latest('created_at')->get();
        } elseif ($user->role === 'admin') {
            $panens = RencanaPanen::with(['petani.user', 'produk'])
                ->latest('created_at')->get();
        }
        return view('history.index', compact('panens'));
    }

    public function detail()
    {
        return view('history.detail');
    }
    public function store()
    {
        
    }
    public function edit()
    {
        return view('history.edit');
    }
    public function destroy()
    {
        return view('history.destroy');
    }
    public function show()
    {
        return view('history.show');
    }
    public function update()
    {
        return view('history.update');
    }
    public function create()
    {
        return view('history.create');
    }
}
