<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tiket Donor Darah Indo Teknik</title>
</head>
<body>
    <h2>Selamat! Pendaftaran Anda Berhasil!</h2>
    <p>Halo, <strong>{{ $participant->name }}</strong></p>
    <p>Terima kasih sudah mendaftar di acara <strong>Donor Darah 20 Tahun Indo Teknik</strong>.</p>
    <h3>Detail Pendaftaran:</h3>
    <ul>
        <li>Nama: <strong>{{ $participant->name }}</strong></li>
        <li>Umur: <strong>{{ $participant->umur }} tahun</strong></li>
        <li>Golongan Darah: <strong>{{ $participant->golongan_darah }}</strong></li>
        <li>Kode Tiket: <strong>{{ $participant->ticket_code }}</strong></li>
        <li>Sesi: <strong>{{ $sessionLabel }}</strong></li>
    </ul>
    <p><strong>Senin, 10 November 2025</strong><br>
    <strong>Indo Teknik, Jl. Riau Ujung No. 898-906, Pekanbaru</strong><br>
    <a href="https://share.google/Bon56WXpiLgXvtqgN">Lihat di Google Maps</a></p>
    <p><strong>Catatan:</strong></p>
    <ol>
        <li>Datang sesuai sesi yang dipilih</li>
        <li>Tunjukkan kode tiket di meja registrasi</li>
        <li>Pastikan tubuh sehat & cukup istirahat sebelum donor</li>
    </ol>
    <p>Terima kasih atas kepedulian Anda!<br>
    Sampai jumpa di lokasi!</p>
    <p><strong>INDO TEKNIK</strong></p>
</body>
</html>
