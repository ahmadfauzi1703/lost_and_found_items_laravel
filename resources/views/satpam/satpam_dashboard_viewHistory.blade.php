<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>History Claim Barang - Lost and Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
                    <a href="{{ route('satpam.dashboard.create') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-box'></i> Add Items</a>
                </li>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.view') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-list-ul'></i> List Item</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.createClaim') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-clipboard'></i> Create Claim Items</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.viewHistory') }}" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bx-history'></i> Item Claim History</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.profile') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-user'></i> Satpam Profile</a>
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
                <h2 class="text-2xl font-semibold mb-4">Item Claim History</h2>

                <!-- Tabel History -->
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#393646]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">No</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Picture</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Name</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Pengklaim</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Tanggal Klaim</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Status</th>
                                            <!-- <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-white uppercase">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($claims as $index => $claim)
                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($claim->item && $claim->item->photo_path)
                                                <img src="{{ asset('storage/' . $claim->item->photo_path) }}" alt="{{ $claim->item->item_name ?? 'Item' }}" class="h-16 w-16 object-cover rounded">
                                                @else
                                                <div class="h-16 w-16 bg-gray-200 flex items-center justify-center rounded">
                                                    <i class='bx bx-image text-gray-400 text-2xl'></i>
                                                </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $claim->item->item_name ?? 'Barang tidak ditemukan' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <p class="font-medium">{{ $claim->claimer_name }}</p>
                                                <p class="text-xs text-gray-500">NIM: {{ $claim->claimer_nim }}</p>
                                                <p class="text-xs text-gray-500">{{ $claim->claimer_email }}</p>
                                                <p class="text-xs text-gray-500">{{ $claim->claimer_phone }}</p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                {{ \Carbon\Carbon::parse($claim->claim_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <span class="px-2 py-1 rounded text-white text-xs font-medium
                                    @if($claim->status == 'Claimed') 'bg-green-500'
                                    @elseif($claim->status == 'pending') 'bg-yellow-500'
                                    @elseif($claim->status == 'rejected')'bg-red-500'
                                    @else 'bg-blue-500'
                                    @endif">
                                                    {{ $claim->status }}
                                                </span>
                                            </td>
                                            <!-- <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="javascript:void(0)" onclick="viewClaimDetails('{{ $claim->id }}')" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden">
                                                        <i class='bx bx-info-circle'></i> Detail
                                                    </a>
                                                </div>
                                            </td> -->
                                        </tr>
                                        @empty
                                        <tr class="bg-white">
                                            <td colspan="7" class="px-6 py-4 text-sm text-gray-800 text-center">Belum ada data klaim</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#6D5D6E] text-white text-center py-4 w-full mt-auto">
        Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
    </footer>
</body>

</html>