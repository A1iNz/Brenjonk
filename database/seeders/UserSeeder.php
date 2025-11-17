<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // 1. Buat User Admin
            User::create([
                'name' => 'Vall',
                'nama_lngkp' => 'Vallery Angelly Loppies',
                'password' => Hash::make('vall1234'),
                'role' => 'admin',
            ]);

            // 2. Buat User Petani
            $petaniUser = User::create([
                'name' => 'Aii',
                'nama_lngkp' => 'Gilang Fajar Nur Ainun',
                'password' => Hash::make('awokawok'),
                'role' => 'petani',
            ]);

            // 3. Buat data petani yang berelasi dengan user petani
            $petaniUser->petani()->create([
                'nama' => $petaniUser->name,
                'kode' => 'PTN-001',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}


