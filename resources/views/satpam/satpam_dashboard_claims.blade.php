<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.pwa')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Staff Kampus | Ringkasan Klaim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    {{-- Ringkasan klaim untuk staff kampus sebelum diteruskan ke admin --}}
    <div class="flex flex-grow">
        <div class="w-[15rem] bg-[#393646] text-white sticky top-0 h-screen overflow-y-auto">
            <img class="h-[6rem] m-auto mt-[1rem]" src="{{ asset('assets/img/logo-arka-white.png') }}" alt="Logo" />
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
                    <a href="{{ route('satpam.dashboard.createClaim') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-clipboard'></i> Buat Klaim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.claims') }}" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bx-user-check'></i> Ringkasan Klaim</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.viewHistory') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-history'></i> Riwayat Klaim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.profile') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-user'></i> Profil Staff Kampus</a>
                </li>
            </ul>
            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block px-4 py-2 text-red-500 hover:text-white"><i class='bx bxs-exit'></i> Logout</button>
                </form>
            </div>
        </div>

        <div class="flex-1 p-3">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Ringkasan Klaim</h1>
                        <p class="text-sm text-gray-500">Pantau siapa yang mengajukan klaim dan bukti yang disertakan sebelum diserahkan ke Admin.</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-700">
                        <p>Total klaim: <span class="font-semibold">{{ number_format($claims->total()) }}</span></p>
                    </div>
                </div>

                {{-- Filter klaim yang diajukan pengguna --}}
                <form method="GET" action="{{ route('satpam.dashboard.claims') }}" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari klaim</label>
                        <input id="search" name="search" type="text" value="{{ $searchTerm }}" placeholder="Nama barang atau pengklaim" class="w-full rounded-md border-gray-300 focus:border-[#5C5470] focus:ring-[#5C5470]">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" class="w-full rounded-md border-gray-300 focus:border-[#5C5470] focus:ring-[#5C5470]">
                            <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua status</option>
                            <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ ($statusFilter ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ ($statusFilter ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#5C5470] text-white rounded-md hover:bg-[#4a475c]"><i class='bx bx-search mr-1'></i> Terapkan</button>
                    </div>
                </form>

                <div class="mt-6 overflow-x-auto">
                    {{-- Daftar klaim dengan bukti dan status verifikasi staff kampus --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#393646] text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Pengklaim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Bukti</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($claims as $claim)
                                @php
                                    $proofPath = $claim->proof_document;
                                    $proofUrl = $proofPath ? asset('storage/' . $proofPath) : null;
                                    $extension = $proofPath ? strtolower(pathinfo($proofPath, PATHINFO_EXTENSION)) : null;
                                    $isImage = in_array($extension, ['jpg','jpeg','png','gif','bmp','webp']);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 align-top text-sm text-gray-800">
                                        <p class="font-semibold">{{ $claim->item->item_name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $claim->item->category ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Masuk: {{ $claim->claim_date ? \Carbon\Carbon::parse($claim->claim_date)->format('d M Y H:i') : '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 align-top text-sm text-gray-800">
                                        <p class="font-medium">{{ $claim->claimer_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $claim->claimer_email }}</p>
                                        <p class="text-xs text-gray-500">{{ $claim->claimer_phone }}</p>
                                        @if($claim->claimer_nim)
                                            <p class="text-xs text-gray-500 mt-1">NIM: {{ $claim->claimer_nim }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 align-top text-sm text-gray-800 space-y-2">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Deskripsi</p>
                                            <p>{{ $claim->ownership_proof ?? 'Tidak ada deskripsi bukti' }}</p>
                                        </div>
                                        <div>
                                            @if($proofUrl)
                                                @if($isImage)
                                                    <a href="{{ $proofUrl }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                                                        <img src="{{ $proofUrl }}" alt="Bukti" class="w-16 h-16 object-cover rounded border">
                                                        <span>Lihat bukti</span>
                                                    </a>
                                                @else
                                                    <a href="{{ $proofUrl }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                                        <i class='bx bx-file mr-1'></i> Dokumen bukti
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400 italic">Tidak ada file terlampir</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($claim->status === 'approved') bg-green-100 text-green-700
                                            @elseif($claim->status === 'rejected') bg-red-100 text-red-700
                                            @elseif($claim->status === 'pending') bg-yellow-100 text-yellow-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($claim->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 align-top text-sm text-gray-700">
                                        @if($claim->notes)
                                            <p>{{ $claim->notes }}</p>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada klaim yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $claims->links() }}
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#6D5D6E] text-white text-center py-4 w-full mt-auto">
        Dibuat dengan dY'T oleh Ac 2025 Sipanang
    </footer>
</body>

</html>
