<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Barang - Lost and Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tambahkan di bagian <head> -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Sidebar -->
    <div class="flex flex-grow">
        <div class="w-[15rem] bg-[#393646] text-white sticky top-0 h-screen overflow-y-auto">
            <img
                class="h-[6rem] m-auto mt-[1rem]"
                src="{{ asset('assets/img/laflogoputih.png') }}" />
            <ul class="mt-6 space-y-2">
                <li>
                    <a href="{{ route('satpam_dashboard') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-dashboard'></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.create') }}" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bx-box'></i> Add Items</a>
                </li>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.view') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-list-ul'></i> List Item</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.createClaim') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-clipboard'></i> Laporan Claim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.viewHistory') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-history'></i> History Claim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.profile') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-user'></i> Profile Satpam</a>
                </li>
            </ul>
            <!-- Logout Button -->
            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block px-4 py-2 text-red-500 hover:text-white"><i class='bx bxs-exit'></i> Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-3">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Add New Items</h2>

                @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('satpam.dashboard.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                            <input type="text" name="item_name" value="{{ old('item_name') }}" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="type" class="w-full p-2 border border-gray-300 rounded" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="hilang" {{ old('type') == 'hilang' ? 'selected' : '' }}>Barang Hilang</option>
                                <option value="ditemukan" {{ old('type') == 'ditemukan' ? 'selected' : '' }}>Barang Ditemukan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category" class="w-full p-2 border border-gray-300 rounded" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Elektronik" {{ old('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                <option value="Handphone" {{ old('category') == 'Handphone' ? 'selected' : '' }}>Handphone</option>
                                <option value="Aksesoris" {{ old('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                <option value="Pakaian" {{ old('category') == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                                <option value="Dokumen" {{ old('category') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dilaporkan Oleh</label>
                            <input type="text" name="report_by" value="{{ old('report_by') }}" class="w-full p-2 border border-gray-300 rounded" placeholder="Nama pelapor (opsional)">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika dilaporkan oleh Anda sendiri</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian</label>
                            <div id="map" class="w-full h-64 bg-gray-200 rounded-lg"></div>
                            <!-- Input tersembunyi untuk koordinat -->
                            <input type="hidden" name="location" id="location-coordinates" value="-6.973250, 107.630339">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kejadian</label>
                            <input type="date" name="date_of_event" value="{{ old('date_of_event') }}" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Kontak</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border border-gray-300 rounded">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full p-2 border border-gray-300 rounded" placeholder="Nomor WhatsApp/Telepon">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                            <input type="file" name="photo" class="w-full p-2 border border-gray-300 rounded">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="4" class="w-full p-2 border border-gray-300 rounded">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="bg-[#6D5D6E] text-white text-center py-4 w-full mt-auto">
        Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
    </footer>

    <!-- Maps Pin -->
    <script>
        // Koordinat Telkom University, Bandung
        const telkomUniversityCoords = [-6.973250, 107.630339];

        // Inisialisasi peta
        const map = L.map('map').setView(telkomUniversityCoords, 16); // Zoom level 16 untuk lebih detail

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker di Telkom University
        const marker = L.marker(telkomUniversityCoords, {
            draggable: true
        }).addTo(map);

        // Perbarui koordinat saat marker dipindahkan
        marker.on('moveend', (e) => {
            const {
                lat,
                lng
            } = e.target.getLatLng();
            console.log(`Latitude: ${lat}, Longitude: ${lng}`);
            document.getElementById('location-coordinates').value = `${lat}, ${lng}`;
        });
    </script>
</body>

</html>