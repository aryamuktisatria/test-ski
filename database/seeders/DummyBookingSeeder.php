<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = \Illuminate\Support\Carbon::now()->format('Y-m-d');
        $dateStr = \Illuminate\Support\Carbon::now()->format('Ymd');
        $bookings = [];

        for ($i = 1; $i <= 28; $i++) {
            $nomorAntri = 'LK' . $dateStr . str_pad($i, 3, '0', STR_PAD_LEFT);
            $bookings[] = [
                'nama' => 'Dummy Person ' . $i,
                'email' => 'dummy' . $i . '@test.com',
                'tanggal_pesan' => $today,
                'kd_stand' => 'LK',
                'nomor_antri' => $nomorAntri,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        \App\Models\AntriStand::insert($bookings);
    }
}
