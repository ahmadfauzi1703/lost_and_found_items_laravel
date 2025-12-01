<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.pwa')
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | Claim Verification</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
  <div class="flex h-screen">
    {{-- Sidebar navigasi admin --}}
    <div class="w-[15rem] bg-[#124076] text-white">
      <img class="h-[5rem] m-auto mt-[1rem]" src="{{ asset('assets/img/logo-arka-white.png') }}" alt="Logo" />
      <ul class="mt-6 space-y-2">
        <li>
          <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-dashboard'></i> Dashboard</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_approval') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-check-circle'></i> Persetujuan</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_lost') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Barang Hilang</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_found') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Barang Temuan</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_user') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-user-circle'></i> Pengguna</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_claims') }}" class="block px-4 py-2 bg-[#1E5CB8]"><i class='bx bx-clipboard'></i> Validasi klaim</a>
        </li>
      </ul>
      <div class="mt-6">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block px-4 py-2 text-red-500 hover:text-white"><i class='bx bxs-exit'></i> Logout</button>
        </form>
      </div>
    </div>

    <div class="flex-1 p-6 overflow-y-auto">
      <div class="flex flex-col gap-4">
        <div class="flex items-start justify-between flex-col sm:flex-row gap-4">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900">Claim Verification</h1>
            <p class="text-sm text-gray-500">Tinjau bukti klaim yang masuk dan tentukan kelayakannya.</p>
          </div>
          {{-- Ringkasan status klaim untuk memantau progres --}}
          <div class="bg-white shadow rounded-lg px-4 py-3 flex flex-col sm:flex-row gap-3 sm:items-center">
            <div class="text-sm text-gray-600">Pending: <span class="font-semibold text-yellow-600">{{ $statusSummary['pending'] ?? 0 }}</span></div>
            <div class="text-sm text-gray-600">Approved: <span class="font-semibold text-green-600">{{ $statusSummary['approved'] ?? 0 }}</span></div>
            <div class="text-sm text-gray-600">Rejected: <span class="font-semibold text-red-600">{{ $statusSummary['rejected'] ?? 0 }}</span></div>
          </div>
        </div>

        {{-- Form filter pencarian klaim berdasarkan kata kunci dan status --}}
        <form method="GET" action="{{ route('admin_dashboard_claims') }}" class="bg-white shadow rounded-lg p-4 flex flex-col gap-3 sm:flex-row sm:items-end">
          <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari klaim</label>
            <input type="text" name="search" id="search" value="{{ $searchTerm }}" placeholder="Nama barang atau pengklaim" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status" class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
              <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
              <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="approved" {{ ($statusFilter ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
              <option value="rejected" {{ ($statusFilter ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
          </div>
          <div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#124076] text-white rounded-md hover:bg-[#0d3159]"><i class='bx bx-search mr-1'></i> Filter</button>
          </div>
        </form>

        {{-- Tabel daftar klaim yang perlu diverifikasi admin --}}
        <div class="bg-white shadow rounded-lg">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-[#124076] text-white">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Barang</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Pengklaim</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Bukti</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Catatan</th>
                      <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                  </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                {{-- Loop setiap klaim untuk ditampilkan dalam baris tabel --}}
                @forelse($claims as $claim)
                  @php
                    // Siapkan informasi file bukti untuk menentukan cara tampilan (gambar atau dokumen)
                    $proofPath = $claim->proof_document;
                    $proofUrl = $proofPath ? asset('storage/' . $proofPath) : null;
                    $extension = $proofPath ? strtolower(pathinfo($proofPath, PATHINFO_EXTENSION)) : null;
                    $isImage = in_array($extension, ['jpg','jpeg','png','gif','bmp','webp']);
                  @endphp
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-800 align-top">
                      <p class="font-semibold">{{ $claim->item->item_name ?? '-' }}</p>
                      <p class="text-xs text-gray-500">{{ $claim->item->category ?? '-' }}</p>
                      <p class="text-xs text-gray-400 mt-1">Diajukan: {{ $claim->claim_date ? \Carbon\Carbon::parse($claim->claim_date)->format('d M Y H:i') : '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800 align-top">
                      <p class="font-medium">{{ $claim->claimer_name }}</p>
                      <p class="text-xs text-gray-500">{{ $claim->claimer_email }}</p>
                      <p class="text-xs text-gray-500">{{ $claim->claimer_phone }}</p>
                      @if($claim->claimer_nim)
                        <p class="text-xs text-gray-500 mt-1">NIM: {{ $claim->claimer_nim }}</p>
                      @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800 align-top space-y-2">
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
                            <a href="{{ $proofUrl }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800"><i class='bx bx-file mr-1'></i> Dokumen bukti</a>
                          @endif
                        @else
                          <span class="text-xs text-gray-400 italic">Tidak ada file terlampir</span>
                        @endif
                      </div>
                    </td>
                    <td class="px-6 py-4 text-sm align-top">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        @if($claim->status === 'approved') 'bg-green-100' 'text-green-700'
                        @elseif($claim->status === 'rejected') 'bg-red-100' 'text-red-700'
                        @else 'bg-yellow-100' 'text-yellow-700' @endif">
                        {{ ucfirst($claim->status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 align-top">
                      @if($claim->notes)
                        <p>{{ $claim->notes }}</p>
                      @else
                        <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                      @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 align-top">
                      <button
                        type="button"
                        class="w-full inline-flex justify-center items-center px-3 py-2 border rounded-md text-sm font-medium text-[#124076] border-[#124076] hover:bg-[#124076] hover:text-white transition btn-claim-detail"
                        data-item="{{ $claim->item->item_name ?? '-' }}"
                        data-category="{{ $claim->item->category ?? '-' }}"
                        data-date="{{ $claim->item->date_of_event ? \Carbon\Carbon::parse($claim->item->date_of_event)->format('d M Y') : '-' }}"
                        data-description="{{ $claim->item->description ?? '-' }}"
                        data-location="{{ $claim->item->location ?? '-' }}"
                        data-email="{{ $claim->item->email ?? '-' }}"
                        data-phone="{{ $claim->item->phone_number ?? '-' }}"
                        data-photo="{{ $claim->item->photo_path ? asset('storage/'.$claim->item->photo_path) : '' }}"
                        data-proof-image="{{ ($proofUrl && $isImage) ? $proofUrl : '' }}"
                        data-proof-doc="{{ ($proofUrl && !$isImage) ? $proofUrl : '' }}"
                        data-claimer="{{ $claim->claimer_name ?? '-' }}"
                        data-claimer-email="{{ $claim->claimer_email ?? '-' }}"
                        data-claimer-phone="{{ $claim->claimer_phone ?? '-' }}"
                        data-ownership="{{ $claim->ownership_proof ?? '-' }}"
                      >
                        <i class='bx bx-detail '></i> Detail
                      </button>

                      <form action="{{ route('admin.claims.update-status', $claim->id) }}" method="POST" class="space-y-2 mt-3">
                        @csrf
                        <div>
                          <label class="block text-xs font-medium text-gray-600 mb-1">Ubah status</label>
                          <select name="status" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ $claim->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $claim->status === 'approved' ? 'selected' : '' }}>Valid</option>
                            <option value="rejected" {{ $claim->status === 'rejected' ? 'selected' : '' }}>Tidak Valid</option>
                          </select>
                        </div>
                        <div>
                          <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                          <textarea name="notes" rows="2" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan verifikasi">{{ old('notes', $claim->notes) }}</textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-[#124076] text-white rounded-md hover:bg-[#0d3159] text-sm font-medium">
                          <i class='bx bx-save mr-1'></i> Simpan
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada klaim yang perlu ditinjau.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="px-6 py-4">
            {{ $claims->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal detail klaim -->
  <div id="claimDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b">
        <div>
          <h3 class="text-lg font-semibold text-gray-800" id="modalItemName">Nama Barang</h3>
          <p class="text-sm text-gray-500" id="modalItemCategory">Kategori</p>
        </div>
        <button id="modalCloseBtn" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
      </div>
      <div class="grid md:grid-cols-2 gap-4 p-6">
        <div class="space-y-2 text-sm text-gray-700">
          <p><span class="font-semibold">Tanggal Kejadian:</span> <span id="modalItemDate"></span></p>
          <p><span class="font-semibold">Lokasi:</span> <span id="modalItemLocation"></span></p>
          <p><span class="font-semibold">Email:</span> <span id="modalItemEmail"></span></p>
          <p><span class="font-semibold">Nomor Kontak:</span> <span id="modalItemPhone"></span></p>
          <p><span class="font-semibold">Deskripsi:</span> <span id="modalItemDescription"></span></p>
          <p class="pt-2 border-t"><span class="font-semibold">Pengklaim:</span> <span id="modalClaimer"></span></p>
          <p><span class="font-semibold">Email Pengklaim:</span> <span id="modalClaimerEmail"></span></p>
          <p><span class="font-semibold">Kontak Pengklaim:</span> <span id="modalClaimerPhone"></span></p>
          <p><span class="font-semibold">Bukti Kepemilikan:</span> <span id="modalOwnership"></span></p>
        </div>
        <div class="flex flex-col gap-3">
          <div class="w-full h-64 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden">
            <img id="modalItemPhoto" src="" alt="Gambar barang" class="max-h-full max-w-full hidden">
            <span id="modalNoPhoto" class="text-sm text-gray-500">Tidak ada gambar barang</span>
          </div>
          <div class="w-full bg-gray-50 rounded-md p-3 border">
            <p class="text-sm font-semibold text-gray-800 mb-2">Lampiran Bukti Klaim</p>
            <div class="flex items-center justify-center min-h-[160px] bg-white border rounded">
              <img id="modalProofPhoto" src="" alt="Bukti klaim" class="max-h-40 max-w-full hidden">
              <a id="modalProofLink" href="#" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm hidden">Lihat dokumen bukti</a>
              <span id="modalNoProof" class="text-sm text-gray-500">Tidak ada bukti terlampir</span>
            </div>
          </div>
          <button id="modalCloseBtnSecondary" class="px-4 py-2 bg-[#124076] text-white rounded-md hover:bg-[#0d3159]">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('claimDetailModal');
      const closeButtons = [document.getElementById('modalCloseBtn'), document.getElementById('modalCloseBtnSecondary')];
      const itemNameEl = document.getElementById('modalItemName');
      const itemCategoryEl = document.getElementById('modalItemCategory');
      const itemDateEl = document.getElementById('modalItemDate');
      const itemLocationEl = document.getElementById('modalItemLocation');
      const itemEmailEl = document.getElementById('modalItemEmail');
      const itemPhoneEl = document.getElementById('modalItemPhone');
      const itemDescriptionEl = document.getElementById('modalItemDescription');
      const claimerEl = document.getElementById('modalClaimer');
      const claimerEmailEl = document.getElementById('modalClaimerEmail');
      const claimerPhoneEl = document.getElementById('modalClaimerPhone');
      const ownershipEl = document.getElementById('modalOwnership');
      const photoEl = document.getElementById('modalItemPhoto');
      const noPhotoEl = document.getElementById('modalNoPhoto');
      const proofPhotoEl = document.getElementById('modalProofPhoto');
      const proofLinkEl = document.getElementById('modalProofLink');
      const noProofEl = document.getElementById('modalNoProof');

      const fillAndShowModal = (btn) => {
        itemNameEl.textContent = btn.dataset.item || '-';
        itemCategoryEl.textContent = btn.dataset.category || '-';
        itemDateEl.textContent = btn.dataset.date || '-';
        itemLocationEl.textContent = btn.dataset.location || '-';
        itemEmailEl.textContent = btn.dataset.email || '-';
        itemPhoneEl.textContent = btn.dataset.phone || '-';
        itemDescriptionEl.textContent = btn.dataset.description || '-';
        claimerEl.textContent = btn.dataset.claimer || '-';
        claimerEmailEl.textContent = btn.dataset.claimerEmail || '-';
        claimerPhoneEl.textContent = btn.dataset.claimerPhone || '-';
        ownershipEl.textContent = btn.dataset.ownership || '-';

        const photoUrl = btn.dataset.photo || '';
        if (photoUrl) {
          photoEl.src = photoUrl;
          photoEl.classList.remove('hidden');
          noPhotoEl.classList.add('hidden');
        } else {
          photoEl.src = '';
          photoEl.classList.add('hidden');
          noPhotoEl.classList.remove('hidden');
        }

        const proofImg = btn.dataset.proofImage || '';
        const proofDoc = btn.dataset.proofDoc || '';
        proofPhotoEl.classList.add('hidden');
        proofLinkEl.classList.add('hidden');
        noProofEl.classList.add('hidden');

        if (proofImg) {
          proofPhotoEl.src = proofImg;
          proofPhotoEl.classList.remove('hidden');
        } else if (proofDoc) {
          proofLinkEl.href = proofDoc;
          proofLinkEl.classList.remove('hidden');
        } else {
          noProofEl.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
      };

      document.querySelectorAll('.btn-claim-detail').forEach((btn) => {
        btn.addEventListener('click', () => fillAndShowModal(btn));
      });

      closeButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        });
      });

      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        }
      });
    });
  </script>
</body>

</html>
