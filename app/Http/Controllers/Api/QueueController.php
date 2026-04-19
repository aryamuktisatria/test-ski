<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuotaStand;
use App\Models\AntriStand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QueueController extends Controller
{
    //fungsi untuk mengambil data stand
    public function getStands(Request $request)
    {
        $stands = QuotaStand::all();
        $tanggal = $request->query('tanggal');

        $result = [];
        foreach ($stands as $stand) {
            $sisa = $stand->quota;
            if ($tanggal) {
                $tanggalParsed = Carbon::parse($tanggal)->format('Y-m-d');
                $bookedCount = AntriStand::where('kd_stand', $stand->kd_stand)
                    ->where('tanggal_pesan', $tanggalParsed)->count();
                $sisa = $stand->quota - $bookedCount;
            }
            $result[] = [
                'kd_stand' => $stand->kd_stand,
                'nama_stand' => $stand->nama_stand,
                'quota' => $sisa
            ];
        }

        return response()->json($result);
    }

    //fungsi untuk mengambil data antrian
    public function getQueues(Request $request)
    {
        $query = AntriStand::orderBy('id', 'asc');
        if ($request->query('tanggal')) {
            $query->where('tanggal_pesan', Carbon::parse($request->query('tanggal'))->format('Y-m-d'));
        }
        if ($request->query('stand') && $request->query('stand') !== '') {
            $query->where('kd_stand', $request->query('stand'));
        }
        $queues = $query->get();
        return response()->json(['data' => $queues]);
    }

    public function storeQueue(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tanggal_pesan' => 'required|date',
            'kd_stand' => 'required|string'
        ]);

        $tanggal = Carbon::parse($request->tanggal_pesan)->format('Y-m-d');

        // Validasi satu email hanya bisa memesan satu kali Foto dan satu kali Lukis setiap harinya
        $isDuplicate = AntriStand::where('email', $request->email)
            ->where('kd_stand', $request->kd_stand)
            ->where('tanggal_pesan', $tanggal)
            ->exists();

        if ($isDuplicate) {
            return response()->json(['error' => 'Email ini sudah melakukan pemesanan untuk stand ini pada tanggal tersebut.'], 400);
        }

        // Cek kuota
        $stand = QuotaStand::where('kd_stand', $request->kd_stand)->first();
        if (!$stand) {
            return response()->json(['error' => 'Stand tidak valid.'], 400);
        }

        $currentCount = AntriStand::where('kd_stand', $request->kd_stand)
            ->where('tanggal_pesan', $tanggal)
            ->count();

        if ($currentCount >= $stand->quota) {
            return response()->json(['error' => 'Antrian untuk stand ini pada tanggal tersebut sudah penuh. Silakan pilih tanggal yang lain.'], 400);
        }

        // Generate nomor antrian
        // Format: {KD}{YYYY}{MM}{DD}{XXX}
        $dateStr = Carbon::parse($request->tanggal_pesan)->format('Ymd');
        $prefix = $request->kd_stand;

        // Ambil antrian terakhir pada hari tersebut untuk stand tersebut
        $lastQueue = AntriStand::where('kd_stand', $request->kd_stand)
            ->where('tanggal_pesan', $tanggal)
            ->orderBy('id', 'desc')
            ->first();

        $counter = 1;
        if ($lastQueue) {
            $lastCounterStr = substr($lastQueue->nomor_antri, -3);
            $counter = intval($lastCounterStr) + 1;
        }

        $nomorAntri = $prefix . $dateStr . str_pad($counter, 3, '0', STR_PAD_LEFT);

        $antri = AntriStand::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'tanggal_pesan' => $tanggal,
            'kd_stand' => $request->kd_stand,
            'nomor_antri' => $nomorAntri
        ]);

        return response()->json(['success' => true, 'data' => $antri]);
    }
}
