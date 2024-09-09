<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "Daftar Komplain.Perlu_Diproses",
        ];

        foreach ($data as $key => $value) {
            \DB::table('permissions')->insert([
                'name' => $value,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // \App\Models\User::factory(10)->create();
    }
}
