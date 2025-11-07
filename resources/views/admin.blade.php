<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Indo Teknik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        table.dataTable thead { background-color: #2563eb; color: white; }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db; border-radius: 6px; padding: 6px 10px; margin-left: 6px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px; padding: 5px 10px; margin: 2px;
            background-color: white !important; border: 1px solid #2563eb !important;
            color: #2563eb !important; font-weight: 500; transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #2563eb !important; color: white !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #2563eb !important; color: white !important;
        }
        .dataTables_length select {
            border-radius: 6px; padding: 4px; border: 1px solid #d1d5db;
        }
        .modal { display: none; }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-lg p-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b pb-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/indo-teknik.png') }}" alt="Indo Teknik" class="w-12">
                <h1 class="text-2xl font-semibold text-gray-800">Dashboard Admin</h1>
            </div>
            <div class="flex gap-3 mt-4 sm:mt-0">
                <button id="addDataBtn" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Tambah Data
                </button>
                <a href="{{ route('admin.logout') }}" class="bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600 transition">
                    Logout
                </a>
            </div>
        </div>

        <!-- Info Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-100 rounded-xl p-4 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Total Peserta</p>
                <h2 class="text-2xl font-semibold text-gray-800">{{ count($participants) }}</h2>
            </div>
            <div class="bg-green-100 rounded-xl p-4 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Golongan Darah Terbanyak</p>
                <h2 class="text-2xl font-semibold text-gray-800">
                    {{ $participants->groupBy('golongan_darah')->sortByDesc(fn($g) => count($g))->keys()->first() ?? '-' }}
                </h2>
            </div>
            <div class="bg-yellow-100 rounded-xl p-4 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Terakhir Daftar</p>
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ optional($participants->first())->created_at ? $participants->first()->created_at->format('d M Y') : '-' }}
                </h2>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8">
            <h3 class="text-lg font-semibold mb-3 text-blue-700">Sisa Kuota per Sesi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-sm text-gray-700">
                <div>Sesi 1 (09.00 - 10.00): <span class="font-semibold">{{ $sisaKuota['sesi_1'] }}</span> / {{ $kuotaSesi['sesi_1'] }}</div>
                <div>Sesi 2 (10.00 - 11.00): <span class="font-semibold">{{ $sisaKuota['sesi_2'] }}</span> / {{ $kuotaSesi['sesi_2'] }}</div>
                <div>Sesi 3 (11.00 - 12.00): <span class="font-semibold">{{ $sisaKuota['sesi_3'] }}</span> / {{ $kuotaSesi['sesi_3'] }}</div>
                <div>Sesi 4 (13.00 - 14.00): <span class="font-semibold">{{ $sisaKuota['sesi_4'] }}</span> / {{ $kuotaSesi['sesi_4'] }}</div>
                <div>Sesi 5 (14.00 - 15.30): <span class="font-semibold">{{ $sisaKuota['sesi_5'] }}</span> / {{ $kuotaSesi['sesi_5'] }}</div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-hidden border border-gray-200 rounded-xl shadow-sm">
            <table id="participantTable" class="stripe hover w-full text-sm text-gray-700">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Golongan Darah</th>
                        <th class="p-3 text-left">WhatsApp</th>
                        <th class="p-3 text-left">Kode Tiket</th>
                        <th class="p-3 text-left">Sesi</th>
                        <th class="p-3 text-left">Jam Sesi</th>
                        <th class="p-3 text-left">Tanggal Daftar</th>
                        <th class="p-3 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                        @php
                            $jamSesi = [
                                'sesi_1' => '09.00 - 10.00 WIB',
                                'sesi_2' => '10.00 - 11.00 WIB',
                                'sesi_3' => '11.00 - 12.00 WIB',
                                'sesi_4' => '13.00 - 14.00 WIB',
                                'sesi_5' => '14.00 - 15.30 WIB',
                            ];
                        @endphp
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="p-3 font-medium">{{ $p->name }}</td>
                            <td class="p-3">{{ $p->email }}</td>
                            <td class="p-3">{{ $p->golongan_darah }}</td>
                            <td class="p-3">{{ $p->whatsapp }}</td>
                            <td class="p-3">{{ $p->ticket_code }}</td>
                            <td class="p-3 capitalize">{{ str_replace('_', ' ', $p->session) }}</td>
                            <td class="p-3">{{ $jamSesi[$p->session] ?? '-' }}</td>
                            <td class="p-3">{{ $p->created_at->format('d M Y') }}</td>
                            <td class="p-3">
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-medium">
                                    {{ $p->keterangan === 'Bukan VIP' ? 'Umum' : $p->keterangan }}
                                </span>
                                <button class="bg-yellow-600 text-white ml-4 px-5 py-2 rounded-lg hover:bg-yellow-700 transition edit-keterangan-btn" 
                                    data-id="{{ $p->id }}" 
                                    data-keterangan="{{ $p->keterangan }}"
                                >Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                            <td class="p-4 text-center text-gray-500">-</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-11/12 max-w-md relative">
            <h2 class="text-xl font-semibold mb-4 text-center">Tambah Peserta Baru</h2>
            <form method="POST" action="{{ route('admin.storeParticipant') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Golongan Darah</label>
                    <select name="golongan_darah" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                        <option value="Tidak Tahu">Tidak Tahu</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" required class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Umur</label>
                    <input type="number" name="umur" required class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Sesi</label>
                    <select name="session" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih Sesi</option>
                        <option value="sesi_1">Sesi 1 (09.00 - 10.00 WIB)</option>
                        <option value="sesi_2">Sesi 2 (10.00 - 11.00 WIB)</option>
                        <option value="sesi_3">Sesi 3 (11.00 - 12.00 WIB)</option>
                        <option value="sesi_4">Sesi 4 (13.00 - 14.00 WIB)</option>
                        <option value="sesi_5">Sesi 5 (14.00 - 15.30 WIB)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Keterangan</label>
                    <input name="keterangan" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" placeholder="Isi status peserta (VIP / Umum)">
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" id="closeModal" class="px-4 py-2 rounded-lg border text-gray-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Keterangan -->
    <div id="editKeteranganModal" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-11/12 max-w-md relative">
            <h2 class="text-xl font-semibold mb-4 text-center">Edit Keterangan Peserta</h2>
            <form id="editKeteranganForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="participant_id" id="editParticipantId">
                <div>
                    <label class="block text-gray-700 mb-1">Keterangan</label>
                    <input name="keterangan" id="editKeteranganInput" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" id="closeEditKeteranganModal" class="px-4 py-2 rounded-lg border text-gray-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#participantTable').DataTable({
            responsive: true,
            deferRender: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ peserta",
                infoEmpty: "Tidak ada data tersedia",
                zeroRecords: "Data tidak ditemukan",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                },
            },
            pageLength: 10
        });

            const modal = $('#addModal');
            $('#addDataBtn').click(() => {
                modal.css('display', 'flex').hide().fadeIn(200);
            });

            $('#closeModal').click(() => {
                modal.fadeOut(200, () => modal.css('display', 'none'));
            });
            $(window).click(e => {
                if ($(e.target).is(modal)) {
                    modal.fadeOut(200, () => modal.css('display', 'none'));
                }
            });
        });

            // Edit Keterangan
        const editModal = $('#editKeteranganModal');
        const editForm = $('#editKeteranganForm');
        $('.edit-keterangan-btn').click(function() {
            const id = $(this).data('id');
            const keterangan = $(this).data('keterangan');
            $('#editParticipantId').val(id);
            $('#editKeteranganInput').val(keterangan);
            // Ganti action form ke route update, misal: /admin/participant/{id}/keterangan
            editForm.attr('action', '/admin/participant/' + id + '/keterangan');
            editModal.css('display', 'flex').hide().fadeIn(200);
        });
        $('#closeEditKeteranganModal').click(() => {
            editModal.fadeOut(200, () => editModal.css('display', 'none'));
        });
        $(window).click(e => {
            if ($(e.target).is(editModal)) {
                editModal.fadeOut(200, () => editModal.css('display', 'none'));
            }
        });
    </script>
</body>
</html>
