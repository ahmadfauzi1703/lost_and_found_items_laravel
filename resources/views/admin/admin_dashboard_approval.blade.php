<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Approval</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-[15rem] flex-shrink-0 bg-[#124076] text-white">
            <img
                class="h-[5rem] m-auto mt-[1rem]"
                src="{{ asset('assets/img/lostnfoundlogowhite.png') }}" />
            <ul class="mt-6 space-y-2">
                <li>
                    <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-dashboard'></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_approval') }}" class="block px-4 py-2 bg-[#1E5CB8]"><i class='bx bxs-check-circle'></i> Approval</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_lost') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Items Lost</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_found') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Items Found</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_user') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-user-circle'></i> Users</a>
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
        <!-- Main Content -->
        <div class="flex-1 p-3 overflow-hidden  ">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Report Approval</h2>

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#124076]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Name</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Picture</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Category</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Type</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Date</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Description</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Status</th>
                                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-white uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @if(count($pendingItems) > 0)
                                        @foreach($pendingItems as $item)
                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="{{ $item->photo_path ? asset('storage/'.$item->photo_path) : asset('assets/img/no-image.png') }}"
                                                    alt="Item Image"
                                                    class="w-16 h-16 object-cover rounded shadow"
                                                    style=" cursor: pointer;">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->category }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->date_of_event }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate">{{ $item->description }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <span class="px-2 py-1 rounded text-white bg-yellow-500 text-xs font-medium">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button"
                                                        data-id="{{ $item->id }}"
                                                        data-name="{{ $item->item_name }}"
                                                        data-category="{{ $item->category }}"
                                                        data-type="{{ $item->type }}"
                                                        data-date="{{ $item->date_of_event }}"
                                                        data-description="{{ $item->description }}"
                                                        data-photo="{{ $item->photo_path ? asset('storage/'.$item->photo_path) : asset('assets/img/no-image.png') }}"
                                                        data-reporter-name="{{ $item->user ? $item->user->first_name.' '.$item->user->last_name : ($item->reporter_name ?? 'Unknown') }}"
                                                        data-reporter-email="{{ $item->user ? $item->user->email : ($item->email ?? '') }}"
                                                        data-reporter-phone="{{ $item->user ? $item->user->phone : ($item->phone_number ?? $item->phone ?? '') }}"
                                                        data-reporter-nim="{{ $item->user ? $item->user->nim : 'N/A' }}"
                                                        class="detail-btn inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800">
                                                        Detail
                                                    </button>
                                                    <form action="{{ route('admin.approve.item', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-600 hover:text-green-800 focus:outline-hidden focus:text-green-800">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.reject.item', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="bg-white">
                                            <td colspan="7" class="px-6 py-4 text-sm text-gray-800 text-center">No pending items found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                                <!-- Modal Detail Item -->
                                <div id="itemDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden">
                                    <!-- Overlay background -->
                                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" id="modalOverlay"></div>

                                    <!-- Modal content -->
                                    <div class="flex items-center justify-center min-h-screen p-4 relative z-60">
                                        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-auto">
                                            <!-- Modal header -->
                                            <div class="flex items-center justify-between p-4 border-b">
                                                <h3 class="text-xl font-semibold text-gray-800" id="modalTitle">Detail Barang</h3>
                                                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal()">
                                                    <span class="text-2xl">&times;</span>
                                                </button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="p-6">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <!-- Kiri: Foto Barang -->
                                                    <div class="flex flex-col items-center justify-center">
                                                        <img id="modalItemPhoto" src="" alt="Item Photo" class="w-full max-h-64 object-contain rounded-lg border">
                                                    </div>

                                                    <!-- Kanan: Informasi Detail -->
                                                    <div>
                                                        <div class="mb-4">
                                                            <h4 class="text-sm text-gray-500">Nama Barang</h4>
                                                            <p id="modalItemName" class="text-lg font-medium"></p>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h4 class="text-sm text-gray-500">Kategori</h4>
                                                            <p id="modalItemCategory" class="text-lg font-medium"></p>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h4 class="text-sm text-gray-500">Jenis Laporan</h4>
                                                            <p id="modalItemType" class="text-lg font-medium"></p>
                                                        </div>

                                                        <div class="mb-4">
                                                            <h4 class="text-sm text-gray-500">Tanggal Kejadian</h4>
                                                            <p id="modalItemDate" class="text-lg font-medium"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Informasi Kontak -->
                                                <div class="mt-6">
                                                    <h4 class="font-semibold text-gray-800 mb-2">Informasi Pelapor</h4>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Nama</p>
                                                            <p id="modalReporterName" class="font-medium"></p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">NIM</p>
                                                            <p id="modalReporterNIM" class="font-medium"></p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">Email</p>
                                                            <p id="modalReporterEmail" class="font-medium"></p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">Telepon</p>
                                                            <p id="modalReporterPhone" class="font-medium"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Deskripsi -->
                                                <div class="mt-6">
                                                    <h4 class="text-sm text-gray-500">Deskripsi</h4>
                                                    <p id="modalItemDescription" class="text-base mt-1"></p>
                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="flex justify-end p-4 border-t">
                                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600" onclick="closeModal()">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full -z-50">
        Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
    </footer>
</body>

<script>
    // Dapatkan semua tombol dengan class detail-btn
    const detailButtons = document.querySelectorAll('.detail-btn');

    // Tambahkan event listener ke setiap tombol
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const category = this.getAttribute('data-category');
            const type = this.getAttribute('data-type');
            const date = this.getAttribute('data-date');
            const description = this.getAttribute('data-description');
            const photoPath = this.getAttribute('data-photo');
            const reporterName = this.getAttribute('data-reporter-name');
            const reporterEmail = this.getAttribute('data-reporter-email');
            const reporterPhone = this.getAttribute('data-reporter-phone');
            const reporterNIM = this.getAttribute('data-reporter-nim');

            // Sekarang panggil fungsi untuk menampilkan modal
            showItemDetail(id, name, category, type, date, description, photoPath,
                reporterName, reporterEmail, reporterPhone, reporterNIM);
        });
    });

    // Fungsi untuk menampilkan modal detail
    function showItemDetail(id, name, category, type, date, description, photoPath, reporterName, reporterEmail, reporterPhone, reporterNIM) {
        // Set nilai-nilai ke elemen HTML di dalam modal
        document.getElementById('modalItemName').textContent = name;
        document.getElementById('modalItemCategory').textContent = category;
        document.getElementById('modalItemType').textContent = type === 'hilang' ? 'Barang Hilang' : 'Barang Ditemukan';
        document.getElementById('modalItemDate').textContent = date;
        document.getElementById('modalItemDescription').textContent = description;
        document.getElementById('modalItemPhoto').src = photoPath;

        document.getElementById('modalReporterName').textContent = reporterName;
        document.getElementById('modalReporterEmail').textContent = reporterEmail;
        document.getElementById('modalReporterPhone').textContent = reporterPhone;
        document.getElementById('modalReporterNIM').textContent = reporterNIM;

        // Tampilkan modal
        document.getElementById('itemDetailModal').classList.remove('hidden');

        // Mencegah scrolling pada body
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        document.getElementById('itemDetailModal').classList.add('hidden');

        // Mengaktifkan kembali scrolling pada body
        document.body.style.overflow = 'auto';
    }

    // Menutup modal ketika mengklik overlay
    document.getElementById('modalOverlay').addEventListener('click', closeModal);
</script>



</html>