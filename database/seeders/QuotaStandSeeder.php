<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuotaStandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\QuotaStand::insert([
            ['kd_stand' => 'FT', 'nama_stand' => 'Foto', 'quota' => 50, 'created_at' => now(), 'updated_at' => now()],
            ['kd_stand' => 'LK', 'nama_stand' => 'Lukis', 'quota' => 30, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
