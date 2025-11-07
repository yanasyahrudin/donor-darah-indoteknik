<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $participants = Participant::orderBy('created_at', 'desc')->get();

        // Kuota maksimal per sesi (72 per sesi = 360 total)
        $kuotaSesi = [
            'sesi_1' => 72,
            'sesi_2' => 72,
            'sesi_3' => 72,
            'sesi_4' => 72,
            'sesi_5' => 72,
        ];

        // Hitung jumlah peserta tiap sesi
        $jumlahPesertaPerSesi = $participants->groupBy('session')->map(fn($g) => count($g));

        // Hitung sisa kuota tiap sesi
        $sisaKuota = [];
        foreach ($kuotaSesi as $sesi => $kuota) {
            $sisaKuota[$sesi] = $kuota - ($jumlahPesertaPerSesi[$sesi] ?? 0);
        }

        return view('admin', compact('participants', 'sisaKuota', 'kuotaSesi'));
    }

    public function storeParticipant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'golongan_darah' => 'required|string|max:3',
            'whatsapp' => 'required|regex:/^[0-9]+$/|max:20',
            'session' => 'required|in:sesi_1,sesi_2,sesi_3,sesi_4,sesi_5',
            'keterangan' => 'string',
            'umur' => 'integer|min:17|max:65',
        ]);

        $count = Participant::where('session', $request->session)->count();
        if ($count >= 72) {
            return back()->with('error', 'Kuota sesi ini sudah penuh, silakan pilih sesi lain.');
        }

        $lastParticipant = Participant::orderBy('id', 'desc')->first();
        $nextNumber = $lastParticipant ? ((int) substr($lastParticipant->ticket_code, -3)) + 1 : 1;
        $ticketCode = 'DONOR' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $participant = Participant::create([
            'name' => $request->name,
            'golongan_darah' => $request->golongan_darah,
            'whatsapp' => $request->whatsapp,
            'ticket_code' => $ticketCode,
            'session' => $request->session,
            'umur' => $request->umur,
            'umur_valid' => true,
            'sehat' => true,
            'keterangan' => $request->keterangan,
        ]);

        $sessionLabel = [
            'sesi_1' => 'Sesi 1 (09.00 - 10.00 WIB)',
            'sesi_2' => 'Sesi 2 (10.00 - 11.00 WIB)',
            'sesi_3' => 'Sesi 3 (11.00 - 12.00 WIB)',
            'sesi_4' => 'Sesi 4 (13.00 - 14.00 WIB)',
            'sesi_5' => 'Sesi 5 (14.00 - 15.30 WIB)',
        ][$request->session];

        // Pesan WhatsApp
        $message = "🎉 *Selamat! Pendaftaran Anda Berhasil!* 🎉

Halo, *{$participant->name}* 👋
Terima kasih sudah mendaftar di acara *Donor Darah 20 Tahun Indo Teknik* 🩸

🎫 *Detail Pendaftaran:*
Nama: *{$participant->name}*
Golongan Darah: *{$participant->golongan_darah}*
Kode Tiket: *{$participant->ticket_code}*
Sesi: *{$sessionLabel}*

📅 *Senin, 10 November 2025*
📍 *Indo Teknik, Jl. Riau Ujung No. 898-906, Pekanbaru*
*Maps:* https://share.google/Bon56WXpiLgXvtqgN

⚠️ Harap diperhatikan juga:
1. Datang *Sesuai sesi yg di pilih*
2. Tunjukkan *kode ticket* di meja registrasi.
3. Pastikan tubuh *sehat & cukup istirahat* sebelum donor ya.

🙏 Terima kasih atas kepedulianmu!
Sampai jumpa di lokasi!

*INDO TEKNIK*
------------
ITech Smart Chioce
Faster
MKN
Bank Mandiri
Multi Permata Aircond
";

        // Kirim pesan WA via Fonnte
        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $participant->whatsapp,
            'message' => $message,
        ]);

        if ($response->successful()) {
            $participant->update(['is_sent' => true]);
        }

        return redirect()->back()->with('success', 'Peserta berhasil ditambahkan dan pesan WA sudah dikirim.');
    }

    public function updateKeterangan(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);
        $participant->keterangan = $request->keterangan;
        $participant->save();

        return redirect()->back()->with('success', 'Keterangan berhasil diupdate.');
    }
}
