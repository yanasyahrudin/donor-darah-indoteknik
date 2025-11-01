<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Indo Teknik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/IT.svg">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-xl w-11/12 sm:w-96 p-8">
        <!-- Logo dan Judul -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('assets/images/indo-teknik.png') }}" alt="Indo Teknik" class="w-20 mb-3">
            <h2 class="text-2xl font-semibold text-gray-800">Login Admin</h2>
            <p class="text-sm text-gray-500">Akses dashboard Indo Teknik</p>
        </div>

        <!-- Pesan Error -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 text-sm p-3 rounded mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 mb-1 text-sm">Username</label>
                <input type="text" name="username" placeholder="Masukkan username"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block text-gray-700 mb-1 text-sm">Password</label>
                <input type="password" name="password" placeholder="Masukkan password"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                Masuk
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} Indo Teknik. Semua hak dilindungi.
        </div>
    </div>

</body>
</html>