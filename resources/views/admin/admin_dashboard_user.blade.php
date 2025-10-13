<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Users - Sipanang</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
          <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-dashboard'></i> Dashboard</a>
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
          <a href="{{ route('admin_dashboard_user') }}" class="block px-4 py-2 bg-[#1E5CB8]"><i class='bx bxs-user-circle'></i> Users</a>
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
        <h2 class="text-2xl font-semibold mb-4">Users Management</h2>
        <div class="flex justify-between mb-4">
          <form method="GET" action="{{ route('admin_dashboard_user') }}" class="flex space-x-4">
            <!-- Search by Name -->
            <input
              type="text"
              name="search"
              value="{{ request('search') }}"
              class="p-2 bg-gray-100 rounded-lg border border-gray-300"
              placeholder="Search Users by Name" />

            <!-- Filter by Role -->
            <select name="role" class="p-2 bg-gray-100 rounded-lg border border-gray-300 w-48">
              <option value="">All Roles</option>
              <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
              <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="satpam" {{ request('role') == 'satpam' ? 'selected' : '' }}>Satpam</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">Filter</button>

            @if(request('search') || request('role'))
            <a href="{{ route('admin_dashboard_user') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Clear</a>
            @endif
          </form>
          <!-- Filter Info Section -->
          @if(request('search') || request('role'))
          <div class="mb-4">
            <p class="text-sm text-gray-600">
              Filter aktif:
              @if(request('search'))
              <span class="font-medium">Nama: "{{ request('search') }}"</span>
              @endif

              @if(request('role'))
              @if(request('search')) | @endif
              <span class="font-medium">Role: "{{ ucfirst(request('role')) }}"</span>
              @endif

              ({{ $users->count() }} user ditemukan)
            </p>
          </div>
          @endif
        </div>

        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-[#124076]">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Email</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Role</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-white uppercase">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $user->first_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $user->email }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                        <span class="px-2 py-1 rounded text-xs font-medium
                      {{ $user->role == 'admin' ? 'bg-green-100 text-green-800' : 
                         ($user->role == 'satpam' ? 'bg-blue-100 text-blue-800' : 
                         'bg-gray-100 text-gray-800') }}">
                          {{ ucfirst($user->role) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <button
                          onclick="openEditModal('{{ $user->id }}', '{{ $user->first_name }}', '{{ $user->role }}')"
                          class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-hidden">
                          Edit
                        </button>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline delete-form">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden ml-2">
                            Delete
                          </button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr class="bg-white">
                      <td colspan="4" class="px-6 py-4 text-sm text-gray-800 text-center">No users found</td>
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
  <!-- Modal -->
  <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50  items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
      <h2 class="text-2xl font-semibold mb-4">Edit User Role</h2>
      <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT') <!-- Menandakan bahwa ini adalah request PUT -->
        <!-- User ID (hidden input) -->
        <input type="hidden" id="editUserId" name="id">

        <!-- Name -->
        <div class="mb-4">
          <label for="editUserName" class="block text-sm font-medium">Name</label>
          <input type="text" id="editUserName" name="name" class="p-2 w-full border border-gray-300 rounded-lg" readonly>
        </div>

        <!-- Role -->
        <div class="mb-4">
          <label for="editRole" class="block text-sm font-medium">Role</label>
          <select id="editRole" name="role" class="p-2 w-full border border-gray-300 rounded-lg">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="satpam">Satpam</option>
          </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
          <button type="submit" id="saveEditBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
        </div>
      </form>


    </div>
  </div>

  <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full -z-50">
    Dibuat dengan 💙 oleh © 2025 Sipanang Team
  </footer>

  <!-- Scripting JS -->

  <!-- Sweet Alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- edit -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const saveButton = document.getElementById('saveEditBtn');
      const form = saveButton.closest('form');

      form.addEventListener('submit', function(e) {
        e.preventDefault(); // biar gak langsung reload

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST', // Karena kamu pakai method spoofing
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
          })
          .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.text(); // atau .json() tergantung response dari controllermu
          })
          .then(data => {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Role pengguna berhasil diperbarui!',
              confirmButtonText: 'OK'
            }).then(() => {
              location.reload(); // reload biar tabel update
            });
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              text: 'Terjadi kesalahan saat memperbarui.',
            });
          });
      });
    });
  </script>

  <!-- delete -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const deleteForms = document.querySelectorAll('.delete-form');

      deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault(); // cegah submit langsung

          Swal.fire({
            title: 'Yakin ingin menghapus user ini?',
            text: "Tindakan ini tidak bisa dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit(); // lanjutkan submit form jika konfirmasi
            }
          });
        });
      });
    });
  </script>



  <script>
    function openEditModal(id, name, role) {
      // Isi field form
      document.getElementById('editUserId').value = id;
      document.getElementById('editUserName').value = name;
      document.getElementById('editRole').value = role;

      // Ganti action form-nya
      const form = document.querySelector('#editModal form');
      form.action = `/admin/users/${id}`;

      // Tampilkan modal
      document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
      document.getElementById('editModal').classList.add('hidden');
    }
  </script>

</body>

</html>