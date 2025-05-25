<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Items Found - Lost and Found</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- BX BX ICONS -->
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
  <!-- Main container -->
  <div class="flex flex-1">
    <!-- Sidebar -->
    <div class="w-[15rem] flex-shrink-0 bg-[#124076] text-white">
      <img
        class="h-[5rem] m-auto mt-[1rem]"
        src="{{ asset('assets/img/lostnfoundlogowhite.png') }}" />
      <ul class="mt-6 space-y-2">
        <li>
          <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-dashboard'></i> Dashboard</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_approval') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-check-circle'></i> Approval</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_lost') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Items Lost</a>
        </li>
        <li>
          <a href="{{ route('admin_dashboard_found') }}" class="block px-4 py-2 bg-[#1E5CB8]"><i class='bx bxs-box'></i> Items Found</a>
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
    <div class="flex-1 p-3 overflow-auto">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Found Items Reports</h2>
        <div class="flex justify-between mb-4">
          <form method="GET" action="{{ route('admin_dashboard_found') }}" class="flex space-x-4">
            <select name="category" class="p-2 bg-gray-100 rounded-lg border border-gray-300">
              <option value="">All Categories</option>
              <option value="Perhiasan Khusus" {{ request('category') == 'Perhiasan Khusus' ? 'selected' : '' }}>Perhiasan Khusus</option>
              <option value="Elektronik" {{ request('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
              <option value="Buku & Dokumen" {{ request('category') == 'Buku & Dokumen' ? 'selected' : '' }}>Buku & Dokumen</option>
              <option value="Tas & Dompet" {{ request('category') == 'Tas & Dompet' ? 'selected' : '' }}>Tas & Dompet</option>
              <option value="Perlengkapan Pribadi" {{ request('category') == 'Perlengkapan Pribadi' ? 'selected' : '' }}>Perlengkapan Pribadi</option>
              <option value="Peralatan Praktikum" {{ request('category') == 'Peralatan Praktikum' ? 'selected' : '' }}>Peralatan Praktikum</option>
              <option value="Aksesori" {{ request('category') == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
              <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input
              type="text"
              name="search"
              value="{{ request('search') }}"
              class="p-2 bg-gray-100 rounded-lg border border-gray-300"
              placeholder="Search Items" />
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">Filter</button>
            @if(request('category') || request('search'))
            <a href="{{ route('admin_dashboard_found') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Clear</a>
            @endif
          </form>

          <!-- Tambahkan setelah form filter -->
          <div class="mb-4">
            @if(request('category') || request('search'))
            <p class="text-sm text-gray-600">
              Filter aktif:
              @if(request('category'))
              <span class="font-medium">Kategori: "{{ request('category') }}"</span>
              @endif

              @if(request('search'))
              @if(request('category')) | @endif
              <span class="font-medium">Pencarian: "{{ request('search') }}"</span>
              @endif

              ({{ $foundItems->count() }} item ditemukan)
            </p>
            @else
            <p class="text-sm text-gray-600">Menampilkan semua item: {{ $foundItems->count() }} item</p>
            @endif
          </div>
        </div>

        <!-- Table dengan max-height dan overflow untuk scroll di tabel saja -->
        <div class="flex flex-col">
          <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
              <div class="overflow-y-auto max-h-[60vh]"> <!-- Ini yang akan membuat scrollbar di tabel -->
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-[#124076] sticky top-0"> <!-- Sticky header -->
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Category</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Date of Events</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Description</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Location</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Email Report</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Phone</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Picture</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Status</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-white uppercase">Actions</th>
                    </tr>
                  </thead>

                  <tbody class="divide-y divide-gray-200">
                    @foreach($foundItems as $item)
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $item->item_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->category }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->date_of_event }}</td>
                      <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate">{{ $item->description }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->location }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->phone_number }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                        @if($item->photo_path)
                        <img src="{{ asset('storage/' . $item->photo_path) }}" class="w-16 h-16 object-cover rounded" alt="Item photo">
                        @else
                        <span class="text-gray-400">No photo</span>
                        @endif
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                        <span class="px-2 py-1 rounded text-xs font-medium 
                        {{ $item->status == 'approved' ? 'bg-green-100 text-green-800' : 
                          ($item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                          'bg-red-100 text-red-800') }}">
                          {{ ucfirst($item->status) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <button onclick="" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden focus:text-blue-800">
                          Edit
                        </button>
                        <form action="" method="POST" class="inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800">
                            Delete
                          </button>
                        </form>
                      </td>
                    </tr>
                    @endforeach

                    @if(count($foundItems) === 0)
                    <tr class="bg-white">
                      <td colspan="10" class="px-6 py-4 text-sm text-gray-800 text-center">No items found</td>
                    </tr>
                    @endif
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
    Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
  </footer>

  <!-- Modal -->
  <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
      <h2 class="text-2xl font-semibold mb-4">Edit Found Item</h2>
      <form method="POST" action="admin_update_item.php">
        <input type="hidden" id="editItemId" name="id">
        <input type="hidden" name="redirect_url" value="admin_items_found.php">
        <div class="mb-4">
          <label for="editItemName" class="block text-sm font-medium">Item Name</label>
          <input type="text" id="editItemName" name="item_name" class="p-2 w-full border border-gray-300 rounded-lg">
        </div>
        <div class="mb-4">
          <label for="editCategory" class="block text-sm font-medium">Category</label>
          <input type="text" id="editCategory" name="category" class="p-2 w-full border border-gray-300 rounded-lg">
        </div>
        <div class="mb-4">
          <label for="editDescription" class="block text-sm font-medium">Description</label>
          <textarea id="editDescription" name="description" class="p-2 w-full border border-gray-300 rounded-lg"></textarea>
        </div>
        <div class="flex justify-end space-x-4">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openEditModal(item) {
      document.getElementById('editItemId').value = item.id;
      document.getElementById('editItemName').value = item.item_name;
      document.getElementById('editCategory').value = item.category;
      document.getElementById('editDescription').value = item.description;
      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
      document.getElementById('editModal').classList.add('hidden');
    }
  </script>
</body>

</html>