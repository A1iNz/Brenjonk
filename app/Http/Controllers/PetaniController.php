<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
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
            'nama_lngkp' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kode' => ['required', 'string', 'max:255', 'unique:'.Petani::class],
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat data user baru
            $user = User::create([
                'nama_lngkp' => $request->nama_lngkp,
                'name' => $request->name,
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Petani $petani)
    {
        // load relasi user agar bisa diakses di view
        $petani->load('user');
        return view('petani.edit', compact('petani'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Petani $petani)
    {
        $request->validate([
            'nama_lngkp' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($petani->user_id)],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'kode' => ['required', 'string', 'max:255', Rule::unique('petanis')->ignore($petani->id)],
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data user
            $user = $petani->user;
            $user->nama_lngkp = $request->nama_lngkp;
            $user->name = $request->name;
            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // 2. Update data petani
            $petani->nama = $request->name;
            $petani->kode = $request->kode;
            $petani->save();

            DB::commit();

            return redirect()->route('petani')->with('success', 'Data petani berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Gagal memperbarui data petani. Silakan coba lagi. Pesan Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petani $petani)
    {
        try {
            // Karena relasi di database menggunakan onDelete('cascade'),
            // menghapus user akan otomatis menghapus data petani yang terkait.
            $petani->user()->delete();

            return redirect()->route('petani')->with('success', 'Data petani berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('petani')->with('error', 'Gagal menghapus data petani.');
        }
    }
}
