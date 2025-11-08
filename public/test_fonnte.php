<?php
// Test koneksi ke Fonnte API dan cek IP outgoing server
header('Content-Type: text/plain');

// Cek koneksi ke Fonnte API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.fonnte.com/send");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$result = curl_exec($ch);
if ($result === false) {
    echo "Curl error: " . curl_error($ch) . "\n";
} else {
    echo "Koneksi ke Fonnte API: Sukses\n";
    echo "Response Header:\n";
    echo $result . "\n";
}
curl_close($ch);

// Cek IP outgoing server
$ip = @file_get_contents("https://api.ipify.org");
echo "IP Outgoing Server: $ip\n";
?>
