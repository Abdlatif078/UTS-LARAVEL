<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Absen Masuk
    public function absenMasuk(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $tanggalHariIni = Carbon::now()->toDateString();

        // Cek jika user sudah absen masuk hari ini
        $absen = Absensi::where('user_id', $request->user_id)
                        ->where('tanggal_absensi', $tanggalHariIni)
                        ->first();

        if ($absen) {
            return response()->json([
                'message' => 'Anda sudah absen masuk hari ini',
            ], 400);
        }

        // Tambahkan data absensi masuk
        Absensi::create([
            'user_id' => $request->user_id,
            'tanggal_absensi' => $tanggalHariIni,
            'waktu_masuk' => Carbon::now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Berhasil absen masuk',
        ], 200);
    }

    // Absen Keluar
    public function absenKeluar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $tanggalHariIni = Carbon::now()->toDateString();

        // Cek jika user sudah absen masuk hari ini
        $absen = Absensi::where('user_id', $request->user_id)
                        ->where('tanggal_absensi', $tanggalHariIni)
                        ->first();

        if (!$absen) {
            return response()->json([
                'message' => 'Anda belum absen masuk hari ini',
            ], 400);
        }

        // Cek jika user sudah absen keluar
        if ($absen->waktu_keluar) {
            return response()->json([
                'message' => 'Anda sudah absen keluar hari ini',
            ], 400);
        }

        // Update data absensi keluar
        $absen->update([
            'waktu_keluar' => Carbon::now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Berhasil absen keluar',
        ], 200);
    }

    // Get Data Absensi User
    public function getAbsensiByUser($user_id)
    {
        $absensi = Absensi::where('user_id', $user_id)->get();

        if ($absensi->isEmpty()) {
            return response()->json([
                'message' => 'Data absensi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Data absensi ditemukan',
            'data' => $absensi
        ], 200);
    }
}
