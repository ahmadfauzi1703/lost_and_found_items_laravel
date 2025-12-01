<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.pwa')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Barang - Lost and Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    {{-- Daftar laporan yang dibuat satpam dengan opsi edit lokasi dan detail --}}
    <!-- Sidebar -->
    <div class="flex flex-grow">
        <div class="w-[15rem] bg-[#393646] text-white sticky top-0 h-screen overflow-y-auto z-20">
            <img
                class="h-[6rem] m-auto mt-[1rem]"
                src="{{ asset('assets/img/logo-arka-white.png') }}" />
            <ul class="mt-6 space-y-2">
                <li>
                    <a href="{{ route('satpam_dashboard') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-dashboard'></i> Dasbor</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.create') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-box'></i> Tambah Barang</a>
                </li>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.view') }}" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bx-list-ul'></i> Daftar Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.createClaim') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-clipboard'></i> Buat Klaim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.claims') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-user-check'></i> Ringkasan Klaim</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.viewHistory') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-history'></i> Riwayat Klaim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.profile') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-user'></i> Profil Staff Kampus</a>
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
                <h2 class="text-2xl font-semibold mb-4">Daftar Barang</h2>

                <!-- Flash Message for Success/Error -->
                @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Table for Items -->

                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                {{-- Tabel item staff kampus beserta statusnya --}}
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#393646]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">No</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Gambar Barang</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Nama Barang</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Kategori</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Jenis</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Tanggal Kejadian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($items as $index => $item)
                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($item->photo_path)
                                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->item_name }}" class="h-16 w-16 object-cover rounded">
                                                @else
                                                <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded">
                                                    <i class='bx bx-image text-gray-400 text-2xl'></i>
                                                </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->category }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                @if($item->type == 'hilang')
                                                <span class="px-2 py-1 rounded text-white bg-red-500 text-xs font-medium">Hilang</span>
                                                @else
                                                <span class="px-2 py-1 rounded text-white bg-green-500 text-xs font-medium">Ditemukan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                {{ \Carbon\Carbon::parse($item->date_of_event)->format('d M Y') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="bg-white">
                                <td colspan="8" class="px-6 py-4 text-sm text-gray-800 text-center">Tidak ada barang yang dilaporkan oleh staff kampus</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Edit (Versi Lebih Lebar) -->
                <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-white rounded-lg w-full max-w-3xl p-6 relative">
                            <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-500">
                                <i class='bx bx-x text-2xl'></i>
                            </button>

                            <h3 class="text-lg font-bold mb-4">Edit Item</h3>

                            <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Layout Grid dengan 2 kolom pada layar medium ke atas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                        <input type="text" id="edit_item_name" name="item_name" class="w-full p-2 border border-gray-300 rounded" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                        <select id="edit_category" name="category" class="w-full p-2 border border-gray-300 rounded" required>
                                            <option value="Elektronik">Elektronik</option>
                                            <option value="Dokumen">Dokumen</option>
                                            <option value="Pakaian">Pakaian</option>
                                            <option value="Aksesoris">Aksesoris</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" id="edit_type_hilang" name="type" value="hilang" class="mr-1">
                                                <span>Hilang</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" id="edit_type_found" name="type" value="ditemukan" class="mr-1">
                                                <span>Ditemukan</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                        <input type="date" id="edit_date" name="date_of_event" class="w-full p-2 border border-gray-300 rounded" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                                        <div class="flex items-center space-x-4">
                                            <div id="current_photo_preview" class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <i class='bx bx-image text-gray-400 text-xl'></i>
                                            </div>
                                            <div class="flex-1">
                                                <input type="file" id="edit_photo" name="photo" class="w-full p-1 text-sm">
                                                <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah foto</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2 mb-4"> <!-- Ambil 2 kolom untuk deskripsi -->
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                        <textarea id="edit_description" name="description" rows="2" class="w-full p-2 border border-gray-300 rounded"></textarea>
                                    </div>

                                    <div class="md:col-span-2"> <!-- Ambil 2 kolom untuk peta -->
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian</label>
                                        <div id="map" class="w-full h-52 bg-gray-200 rounded-lg"></div>
                                        <!-- Input tersembunyi untuk koordinat -->
                                        <input type="hidden" name="location" id="location-coordinates" value="-6.973250, 107.630339">
                                    </div>
                                </div>

                                <div class="flex justify-end mt-4">
                                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-500 hover:text-gray-700 mr-2">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer with margin to avoid sidebar -->
    <footer class="bg-[#6D5D6E] text-white text-center py-4 w-full mt-auto">
        Dibuat dengan 💙 oleh © 2025 Sipanang
    </footer>

</body>


<script>
    // Data items dari server
    const items = JSON.parse('{!! json_encode($items ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) !!}');

    // Fungsi untuk membuka modal edit
    function editItem(id) {
        const item = items.find(i => i.id == id);
        if (!item) return;

        // Set form action
        document.getElementById('editForm').action = `/satpam/items/${id}`;

        // Isi form dengan data
        document.getElementById('edit_item_name').value = item.item_name || '';
        document.getElementById('edit_category').value = item.category || '';

        // Set radio buttons
        if (item.type === 'hilang') {
            document.getElementById('edit_type_hilang').checked = true;
        } else {
            document.getElementById('edit_type_found').checked = true;
        }

        // Format date
        if (item.date_of_event) {
            const date = new Date(item.date_of_event);
            const formattedDate = date.toISOString().split('T')[0];
            document.getElementById('edit_date').value = formattedDate;
        }

        document.getElementById('edit_description').value = item.description || '';

        const photoPreview = document.getElementById('current_photo_preview');
        if (item.photo_path) {
            photoPreview.innerHTML = '';
            const img = document.createElement('img');
            img.src = `/storage/${item.photo_path}`;
            img.alt = 'Item Photo';
            img.className = 'w-full h-full object-cover rounded';
            photoPreview.appendChild(img);
        } else {
            photoPreview.innerHTML = '<i class="bx bx-image text-gray-400 text-xl"></i>';
        }

        document.getElementById('edit_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const photoPreview = document.getElementById('current_photo_preview');
            photoPreview.innerHTML = '';

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'New Photo';
                img.className = 'w-full h-full object-cover rounded';
                photoPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        // Tampilkan modal
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

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

</html>
