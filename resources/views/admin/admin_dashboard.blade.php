<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
  <!-- BX BX ICONS -->
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
  <!-- Sidebar -->
  <div class="flex h-screen">
    <div class="w-[15rem] bg-[#124076] text-white">
      <img
        class="h-[5rem] m-auto mt-[1rem]"
        src="{{ asset('assets/img/logo-arka-white.png') }}" />
      <ul class="mt-6 space-y-2">
        <li>
          <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 bg-[#1E5CB8]"><i class='bx bxs-dashboard'></i> Dashboard</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_approval') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-check-circle'></i> Approval</a>
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
        <li>
          <a href="{{ route('admin_dashboard_claims') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bx-clipboard'></i> Claim Verification</a>
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
        <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Unaproved Report -->
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-yellow-600">
            <i class='bx bx-doughnut-chart text-4xl text-yellow-600 mr-4'></i>
            <div>
              <h3 class="text-lg font-semibold">Unaproved Report</h3>
              <p class="text-xl font-bold">{{ $unapprovedCount }}</p> <!-- Ganti angka 0 dengan variabel userCount -->
            </div>
          </div>
          <!-- Total Report -->
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-green-600">
            <i class='bx bxs-group text-3xl text-green-600 mr-4'></i>
            <div>
              <h3 class="text-lg font-semibold">Total Report</h3>
              <p class="text-xl font-bold">{{ $totalReports }}</p> <!-- Ganti angka 0 dengan variabel userCount -->
            </div>
          </div>
          <!-- Total Users -->
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-[#124076]">
            <i class='bx bxs-group text-3xl text-blue-800 mr-4'></i>
            <div>
              <h3 class="text-lg font-semibold">Total Users</h3>
              <p class="text-xl font-bold">{{ $userCount }}</p> <!-- Ganti angka 0 dengan variabel userCount -->
            </div>
          </div>
          <!-- Lost Items -->
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-red-600">
            <i class='bx bxs-help-circle text-3xl text-red-600 mr-4'></i>
            <div>
              <h3 class="text-lg font-semibold">Lost Items Report</h3>
              <p class="text-xl font-bold">{{ $lostItemsCount }}</p>
            </div>
          </div>
          <!-- Found Items -->
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-green-600">
            <i class='bx bxs-check-circle text-3xl text-green-600 mr-4'></i>
            <div>
              <h3 class="text-lg font-semibold">Found Items Report</h3>
              <p class="text-xl font-bold">{{ $foundItemsCount }}</p>
            </div>
          </div>
          <!-- Claimed Items -->
          <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-green-600">
            <i class='bx  bx-box text-3xl text-green-600 mr-4'></i>
            <div>
              <h3 class="text-lg font-semibold">Claimed Items</h3>
              <p class="text-xl font-bold">{{ $claimedItemsCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Recent Activity</h2>

        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class=" bg-[#124076]">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Category</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Date of Events</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Description</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Location</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Email Report</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Phone</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Picture</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $item->item_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->category }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->date_of_event }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate">{{ \Illuminate\Support\Str::limit($item->description, 25, '..') }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->location }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->phone_number }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                        @if($item->photo_path)
                        <img src="{{ asset('storage/' . $item->photo_path) }}" alt="Item Image" class="w-16 h-16 object-cover rounded">
                        @else
                        <img src="{{ asset('Assets/img/no-image.png') }}" alt="No Image" class="w-16 h-16 object-cover rounded">
                        @endif
                      </td>
                    </tr>
                    @endforeach

                    @if(count($items) === 0)
                    <tr class="bg-white">
                      <td colspan="8" class="px-6 py-4 text-sm text-gray-800 text-center">No items found</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Recent Claim Evidence</h2>
        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class=" bg-[#124076]">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Pengklaim</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Bukti</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Status</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Tanggal Klaim</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @forelse($recentClaims as $claim)
                    @php
                      $proofPath = $claim->proof_document;
                      $proofUrl = $proofPath ? asset('storage/'.$proofPath) : null;
                      $extension = $proofPath ? strtolower(pathinfo($proofPath, PATHINFO_EXTENSION)) : null;
                      $isImage = in_array($extension, ['jpg','jpeg','png','gif','bmp','webp']);
                    @endphp
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                      <td class="px-6 py-4 text-sm text-gray-800 align-top">
                        <p class="font-semibold">{{ $claim->item->item_name ?? 'Item tidak ditemukan' }}</p>
                        <p class="text-xs text-gray-500">{{ $claim->item->category ?? '-' }}</p>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-800 align-top">
                        <p class="font-medium">{{ $claim->claimer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $claim->claimer_email }}</p>
                        <p class="text-xs text-gray-500">{{ $claim->claimer_phone }}</p>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-800 align-top">
                        <div>
                          <p class="text-xs text-gray-500">Deskripsi Bukti</p>
                          <p class="text-sm font-medium">{{ $claim->ownership_proof ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div class="mt-2">
                          @if($proofUrl)
                            @if($isImage)
                              <button
                                type="button"
                                class="outline-none"
                                data-proof-url="{{ $proofUrl }}"
                                data-proof-title="Bukti {{ $claim->item->item_name ?? 'barang' }}"
                                data-proof-description="{{ $claim->ownership_proof ?? '' }}">
                                <img src="{{ $proofUrl }}" alt="Bukti {{ $claim->item->item_name ?? 'barang' }}" class="h-16 w-16 object-cover rounded border shadow">
                              </button>
                            @else
                              <a href="{{ $proofUrl }}" target="_blank" class="text-blue-600 text-sm underline">Lihat Dokumen</a>
                            @endif
                          @else
                            <span class="text-xs text-gray-400">Tidak ada file bukti</span>
                          @endif
                        </div>
                        @if(!empty($claim->notes))
                        <div class="mt-2">
                          <p class="text-xs text-gray-500">Catatan</p>
                          <p class="text-sm">{{ $claim->notes }}</p>
                        </div>
                        @endif
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-800 align-top">
                        <span class="px-2 py-1 rounded text-xs font-medium 
                          {{ $claim->status == 'approved' || $claim->status == 'Claimed' ? 'bg-green-100 text-green-800' : 
                            ($claim->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-red-100 text-red-800') }}">
                          {{ ucfirst($claim->status) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-800 align-top">
                        {{ $claim->claim_date ? \Carbon\Carbon::parse($claim->claim_date)->format('d M Y') : '-' }}
                      </td>
                    </tr>
                    @empty
                    <tr class="bg-white">
                      <td colspan="5" class="px-6 py-4 text-sm text-gray-800 text-center">Belum ada klaim yang tercatat</td>
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
  <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full -z-50">
    Dibuat dengan dY'T oleh Ac 2025 Sipanang Team
  </footer>

  <!-- Modal Preview Bukti -->
  <div id="proofModal" class="hidden fixed inset-0 z-50">
    <div data-proof-overlay class="absolute inset-0 bg-black bg-opacity-60"></div>
    <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
      <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b">
          <h3 id="proofModalTitle" class="text-lg font-semibold">Barang Bukti</h3>
          <button type="button" data-proof-close class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="p-4">
          <img id="proofModalImage" src="" alt="Barang Bukti" class="w-full max-h-[70vh] object-contain rounded">
          <p id="proofModalDescription" class="mt-3 text-sm text-gray-700"></p>
        </div>
        <div class="flex justify-end px-4 py-3 border-t">
          <button type="button" data-proof-close class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</body>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const proofModal = document.getElementById('proofModal');
    if (!proofModal) return;

    const modalImage = document.getElementById('proofModalImage');
    const modalTitle = document.getElementById('proofModalTitle');
    const modalDescription = document.getElementById('proofModalDescription');
    const overlay = proofModal.querySelector('[data-proof-overlay]');
    const closeButtons = proofModal.querySelectorAll('[data-proof-close]');
    const triggers = document.querySelectorAll('[data-proof-url]');

    const openModal = (trigger) => {
      const url = trigger.dataset.proofUrl || '';
      const title = trigger.dataset.proofTitle || 'Barang Bukti';
      const description = trigger.dataset.proofDescription || '';

      modalImage.src = url;
      modalImage.alt = title;
      modalTitle.textContent = title;

      const trimmedDesc = description.trim();
      if (trimmedDesc.length > 0) {
        modalDescription.textContent = trimmedDesc;
        modalDescription.classList.remove('hidden');
      } else {
        modalDescription.textContent = '';
        modalDescription.classList.add('hidden');
      }

      proofModal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    };

    const closeModal = () => {
      proofModal.classList.add('hidden');
      modalImage.src = '';
      document.body.classList.remove('overflow-hidden');
    };

    triggers.forEach(trigger => {
      trigger.addEventListener('click', () => openModal(trigger));
    });

    closeButtons.forEach(button => button.addEventListener('click', closeModal));
    if (overlay) {
      overlay.addEventListener('click', closeModal);
    }

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !proofModal.classList.contains('hidden')) {
        closeModal();
      }
    });
  });
</script>

</html>

