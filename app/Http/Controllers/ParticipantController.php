<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\Http;

class ParticipantController extends Controller
{
    public function create()
    {
        $quota = [
            'sesi_1' => 76 - Participant::where('session', 'sesi_1')->count(),
            'sesi_2' => 77 - Participant::where('session', 'sesi_2')->count(),
            'sesi_3' => 82 - Participant::where('session', 'sesi_3')->count(),
            'sesi_4' => 72 - Participant::where('session', 'sesi_4')->count(),
            'sesi_5' => 75 - Participant::where('session', 'sesi_5')->count(),
        ];

        return view('form', compact('quota'));
    }

    public function store(Request $request)
    {
        // Normalisasi nomor WhatsApp
        $whatsapp = $request->whatsapp;

        // Hapus semua karakter non-angka (spasi, strip, +, dll)
        $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);

        // Ubah format 62xxx menjadi 08xxx untuk konsistensi
        if (substr($whatsapp, 0, 2) === '62') {
            $whatsapp = '0' . substr($whatsapp, 2);
        }

         // Update request dengan nomor yang sudah dinormalisasi
        $request->merge(['whatsapp' => $whatsapp]);

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'golongan_darah' => 'required',
            'whatsapp' => 'required|regex:/^[0-9]+$/|unique:participants,whatsapp',
            'session' => 'required|in:sesi_1,sesi_2,sesi_3,sesi_4,sesi_5',
            'umur_valid' => 'accepted',
            'sehat' => 'accepted',
            'umur' => 'required|integer|min:17|max:65',
        ], [
            'email.required' => 'Email wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Nomor WhatsApp harus berupa angka.',
            'whatsapp.unique' => 'Nomor WhatsApp ini sudah terdaftar. Anda tidak bisa mendaftar 2 kali.',
            'name.required' => 'Nama wajib diisi.',
            'golongan_darah.required' => 'Golongan darah wajib dipilih.',
            'session.required' => 'Sesi wajib dipilih.',
            'umur.required' => 'Umur wajib diisi.',
            'umur.min' => 'Umur minimal 17 tahun.',
            'umur.max' => 'Umur maksimal 65 tahun.',
            'umur_valid.accepted' => 'Anda harus mencentang bahwa umur sudah sesuai.',
            'sehat.accepted' => 'Anda harus mencentang bahwa kondisi sehat.',
        ]);

        $kuotaSesi = [
            'sesi_1' => 76,
            'sesi_2' => 77,
            'sesi_3' => 82,
            'sesi_4' => 72,
            'sesi_5' => 75,
        ];
        $count = Participant::where('session', $request->session)->count();
        if ($count >= ($kuotaSesi[$request->session] ?? 72)) {
            return back()->with('error', 'Kuota sesi ini sudah penuh, silakan pilih sesi lain.');
        }

        $lastParticipant = Participant::orderBy('id', 'desc')->first();
        $nextNumber = $lastParticipant ? ((int) substr($lastParticipant->ticket_code, -3)) + 1 : 1;
        $ticketCode = 'DONOR' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $participant = Participant::create([
            'name' => $request->name,
            'email' => $request->email,
            'golongan_darah' => $request->golongan_darah,
            'whatsapp' => $request->whatsapp,
            'ticket_code' => $ticketCode,
            'session' => $request->session,
            'umur' => $request->umur,
            'umur_valid' => $request->has('umur_valid'),
            'sehat' => $request->has('sehat'),
        ]);

        // Label sesi untuk pesan WA
        $sessionLabel = [
            'sesi_1' => 'Sesi 1 (09.00 - 10.00 WIB)',
            'sesi_2' => 'Sesi 2 (10.00 - 11.00 WIB)',
            'sesi_3' => 'Sesi 3 (11.00 - 12.00 WIB)',
            'sesi_4' => 'Sesi 4 (13.00 - 14.00 WIB)',
            'sesi_5' => 'Sesi 5 (14.00 - 15.30 WIB)',
        ][$request->session];

        $message = "🎉 *Selamat! Pendaftaran Anda Berhasil!* 🎉

Halo, *{$participant->name}* 👋
Terima kasih sudah mendaftar di acara *Donor Darah 20 Tahun Indo Teknik* 🩸

🎫*Detail Pendaftaran:*
Nama: *{$participant->name}*
Umur: *{$participant->umur} tahun*
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
        $waSuccess = false;
        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $participant->whatsapp,
                'message' => $message,
            ]);
            if ($response->successful()) {
                $participant->update(['is_sent' => true]);
                $waSuccess = true;
            }
        } catch (\Exception $e) {
            // log error jika perlu
        }

        // Kirim email SELALU, baik WA sukses atau gagal
        try {
            $sessionLabel = [
                'sesi_1' => 'Sesi 1 (09.00 - 10.00 WIB)',
                'sesi_2' => 'Sesi 2 (10.00 - 11.00 WIB)',
                'sesi_3' => 'Sesi 3 (11.00 - 12.00 WIB)',
                'sesi_4' => 'Sesi 4 (13.00 - 14.00 WIB)',
                'sesi_5' => 'Sesi 5 (14.00 - 15.30 WIB)',
            ][$request->session];
            \Mail::to($request->email)->send(new \App\Mail\ParticipantRegistered($participant, $sessionLabel));
        } catch (\Exception $e) {
            // log error jika perlu
        }

        $notifMsg = "Tiket kamu sudah dikirim via WhatsApp dan Email. Kode Tiket Anda: {$participant->ticket_code} Silahkan di Screenshot.";

        return redirect()->route('form')
            ->with('success', $notifMsg);
    }

    public function index()
    {
        $participants = Participant::latest()->get();
        return view('admin', compact('participants'));
    }
}