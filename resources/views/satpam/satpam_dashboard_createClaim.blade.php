<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.pwa')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Claim - Lost and Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    {{-- Form staff kampus untuk mencatat klaim barang sebelum diverifikasi admin --}}
    <!-- Sidebar -->
    <div class="flex flex-grow">
        <div class="w-[15rem] bg-[#393646] text-white sticky top-0 h-screen overflow-y-auto z-20">
            <img class="h-[6rem] m-auto mt-[1rem]" src="{{ asset('assets/img/logo-arka-white.png') }}" />
            <ul class="mt-6 space-y-2">
                <li>
                    <a href="{{ route('satpam_dashboard') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-dashboard'></i> Dasbor</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.create') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-box'></i> Tambah Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.view') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-list-ul'></i> Daftar Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.createClaim') }}" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bx-clipboard'></i> Buat Klaim Barang</a>
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
                <h2 class="text-2xl font-semibold mb-4">Buat Klaim Barang</h2>

                <!-- Flash Message for Success/Error -->
                @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Form untuk claim barang -->
                {{-- Input detail pengklaim dan bukti kepemilikan --}}
                <form method="POST" action="{{ route('satpam.claims.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Barang <span class="text-xs text-gray-500">({{ $items->count() }} tersedia)</span>
                            </label>
                            <select name="item_id" class="w-full p-2 border border-gray-300 rounded" required>
                                <option value="">-- Pilih Barang --</option>
                                @php $availableItems = 0; @endphp

                                @foreach($items as $item)
                                @if(strtolower($item->status) !== 'claimed')
                                @php $availableItems++; @endphp
                                <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->category }})</option>
                                @endif
                                @endforeach

                                @if($availableItems == 0)
                                <option value="" disabled>Tidak ada barang tersedia untuk diklaim</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengklaim</label>
                            <input type="text" name="claimer_name" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIM Mahasiswa</label>
                            <input type="text" name="claimer_nim" class="w-full p-2 border border-gray-300 rounded"
                                placeholder="Masukkan NIM jika pengklaim adalah mahasiswa">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Pengklaim</label>
                            <input type="email" name="claimer_email" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="text" name="claimer_phone" class="w-full p-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Kepemilikan</label>
                            <textarea name="ownership_proof" rows="3" class="w-full p-2 border border-gray-300 rounded" placeholder="Jelaskan ciri-ciri khusus barang atau bukti kepemilikan lainnya" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti (Opsional)</label>
                            <input type="file" name="proof_document" class="w-full p-2 border border-gray-300 rounded">
                            <p class="text-xs text-gray-500 mt-1">Format yang diterima: JPG, PNG, PDF. Max: 2MB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Klaim</label>
                            <input type="date" name="claim_date" class="w-full p-2 border border-gray-300 rounded" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                            <textarea name="notes" rows="2" class="w-full p-2 border border-gray-300 rounded"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Submit Klaim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#6D5D6E] text-white text-center py-4 w-full mt-auto">
        Dibuat dengan 💙 oleh © 2025 Sipanang
    </footer>
</body>

</html>
