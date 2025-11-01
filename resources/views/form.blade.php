<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Donor Darah Indo Teknik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/IT.svg">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fce4ec, #fff3e0);
            background-attachment: fixed;
        }
    </style>
</head>
<body class="flex flex-col items-center min-h-screen">

    <div class="bg-white mt-8 mb-10 p-6 sm:p-8 rounded-2xl shadow-lg w-11/12 sm:w-96 transition transform hover:scale-[1.02]">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/20th.png') }}" alt="Logo 20th Indo Teknik" class="h-20 object-contain">
        </div>
        <h2 class="text-center text-xl font-bold text-gray-800 mb-5 leading-snug">
            Formulir Pendaftaran<br>Donor Darah Indo Teknik 2025
        </h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <span class="text-gray-700 text-sm">Silakan isi data diri Anda dengan benar pada form pendaftaran di bawah ini.</span>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Nama Lengkap<span class="text-red-500">*</span></label>
                <input type="text" name="name" placeholder="Masukkan nama kamu"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Nomor WhatsApp<span class="text-red-500">*</span></label>
                <input type="text" name="whatsapp" placeholder="0812xxxxxxx"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Golongan Darah<span class="text-red-500">*</span></label>
                <select name="golongan_darah"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-red-400" required>
                    <option value="">-- Pilih Golongan Darah --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="AB">AB</option>
                    <option value="O">O</option>
                    <option value="Tidak Tahu">Tidak Tahu</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Pilih Sesi<span class="text-red-500">*</span></label>
                <select name="session"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-red-400" required>
                    <option value="">-- Pilih Sesi --</option>
                    <option value="sesi_1" @if($quota['sesi_1']==0) disabled @endif>Sesi 1 (09.00 - 10.00)</option>
                    <option value="sesi_2" @if($quota['sesi_2']==0) disabled @endif>Sesi 2 (10.00 - 11.00)</option>
                    <option value="sesi_3" @if($quota['sesi_3']==0) disabled @endif>Sesi 3 (11.00 - 12.00)</option>
                    <option value="sesi_4" @if($quota['sesi_4']==0) disabled @endif>Sesi 4 (13.00 - 14.00)</option>
                    <option value="sesi_5" @if($quota['sesi_5']==0) disabled @endif>Sesi 5 (14.00 - 15.30)</option>
                </select>
            </div>

            <!-- Checkbox umur dan kesehatan -->
            <div class="space-y-2">
                <label class="flex items-center text-gray-700 text-sm">
                    <input type="checkbox" id="umurCheckbox" name="umur_valid" class="mr-2 accent-red-600" required>
                    Saya berumur antara 17 - 65 tahun
                </label>

                <!-- Field umur, tampil setelah dicentang -->
                <div id="umurField" class="hidden mt-2">
                    <label class="block text-gray-700 text-sm mb-1">Tuliskan umur Anda</label>
                    <input type="number" id="umurInput" name="umur" min="17" max="65"
                        placeholder="Contoh: 25"
                        class="border border-gray-300 w-full p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400">
                </div>

                <label class="flex items-center text-gray-700 text-sm">
                    <input type="checkbox" name="sehat" required class="mr-2 accent-red-600">
                    Saya dalam keadaan sehat dan siap donor darah
                </label>
            </div>

            <div>
                <span class="italic text-gray-700 text-sm">*Pastikan nomor WhatsApp Anda aktif dan valid, karena tiket akan dikirim ke nomor ini.</span>
            </div>

            <button type="submit"
                class="bg-red-600 text-white px-4 py-2 rounded-lg w-full font-semibold hover:bg-red-700 transition duration-300">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-6 text-sm text-gray-700">
            Berminat menjadi sponsor acara ini? Hubungi <span class="font-semibold">Anisya (0822-2529-6688)</span>
        </div>
    </div>

    <div class="w-11/12 sm:w-4/5 lg:w-3/4 mb-10">
        <h3 class="text-center text-gray-700 font-semibold mb-5">Didukung oleh</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6 items-center justify-items-center text-center">
            <img src="{{ asset('assets/images/indo-teknik.png') }}" alt="Indo Teknik" class="h-14 sm:h-24 object-contain">  
            <img src="{{ asset('assets/images/itech.svg') }}" alt="Itech" class="h-14 sm:h-24 object-contain">  
            <img src="{{ asset('assets/images/mkn.svg') }}" alt="MKN" class="h-8 sm:h-12 object-contain">
            <img src="{{ asset('assets/images/mandiri.svg') }}" alt="Mandiri" class="h-8 sm:h-12 object-contain">
            <img src="{{ asset('assets/images/pmi.svg') }}" alt="PMI" class="h-8 sm:h-12 object-contain">
            <img src="{{ asset('assets/images/multi-permata.svg') }}" alt="Multi Permata" class="h-8 sm:h-12 object-contain">
        </div>
    </div>

    <script>
        const umurCheckbox = document.getElementById('umurCheckbox');
        const umurField = document.getElementById('umurField');
        const umurInput = document.getElementById('umurInput');

        umurCheckbox.addEventListener('change', function() {
            if (this.checked) {
                umurField.classList.remove('hidden');
                umurInput.setAttribute('required', true);
            } else {
                umurField.classList.add('hidden');
                umurInput.removeAttribute('required');
                umurInput.value = '';
            }
        });
    </script>
</body>
</html>