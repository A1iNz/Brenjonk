<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
class PetaniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $petanis = Petani::with('user')->latest()->get();

        return view('petani.index', compact('petanis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('petani.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kode' => ['required', 'string', 'max:255', 'unique:'.Petani::class],
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat data user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'petani', // Langsung set role sebagai 'petani'
            ]);

            // 2. Buat data petani baru dan hubungkan dengan user_id
            $user->petani()->create([
                'nama' => $request->name, // Mengisi kolom nama di tabel petanis
                'kode' => $request->kode,
            ]);

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return redirect()->route('petani')->with('success', 'Petani baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Jika terjadi error, rollback semua query
            DB::rollBack();

            // Tampilkan error
            throw ValidationException::withMessages(['error' => 'Gagal menyimpan data petani. Silakan coba lagi. Pesan Error: ' . $e->getMessage()]);
        }
    }
}
