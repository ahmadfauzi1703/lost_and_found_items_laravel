<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.pwa')
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile Staff Kampus - Lost and Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    {{-- Profil staff kampus dengan detail akun dan modal edit data diri --}}
    <!-- Sidebar -->
    <div class="flex flex-grow">
        <div class="w-[15rem] bg-[#393646] text-white sticky top-0 h-screen overflow-y-auto">
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
                <li>
                    <a href="{{ route('satpam.dashboard.view') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-list-ul'></i> Daftar Barang</a>
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
                    <a href="{{ route('satpam.dashboard.profile') }}" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bx-user'></i> Profil Staff Kampus</a>
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
                <h2 class="text-2xl font-semibold mb-6">Profile Staff Kampus</h2>

                <!-- Flash Message for Success -->
                @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Profile Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Profile Picture -->
                    <div class="col-span-1 flex flex-col items-center">
                        <div class="w-40 h-40 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden mb-3">
                            @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-full h-full object-cover" alt="Profile Photo">
                            @else
                            <i class='bx bx-user text-7xl text-gray-400'></i>
                            @endif
                        </div>
                        <p class="text-lg font-semibold">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">Security Staff</p>
                    </div>

                    <!-- Profile Details -->
                    <div class="col-span-2">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-[#393646]">Personal Information</h3>

                            <div class="space-y-4">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 font-medium text-gray-600">Name</div>
                                    <div class="md:w-2/3">{{ $user->first_name }} {{ $user->last_name }}</>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 font-medium text-gray-600">Email</div>
                                    <div class="md:w-2/3">{{ $user->email }}</div>
                                </div>

                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 font-medium text-gray-600">Role</div>
                                    <div class="md:w-2/3">Staff Kampus</div>
                                </div>

                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3 font-medium text-gray-600">Joined On</div>
                                    <div class="md:w-2/3">{{ $user->created_at->format('d M Y') }}</div>
                                </div>

                                <div class="mt-6">
                                    <button id="editProfileBtn" class="bg-[#393646] hover:bg-[#4A4458] text-white px-4 py-2 rounded-lg">
                                        <i class='bx bx-edit mr-2'></i> Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg max-w-lg w-full">
            <h3 class="text-xl font-semibold mb-4">Edit Profile</h3>

            <form action="{{ route('satpam.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">
                            First Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                            id="first_name"
                            type="text"
                            name="first_name"
                            value="{{ $user->first_name }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">
                            Last Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                            id="last_name"
                            type="text"
                            name="last_name"
                            value="{{ $user->last_name }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                        id="email"
                        type="email"
                        name="email"
                        value="{{ $user->email }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                        Phone
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                        id="phone"
                        type="text"
                        name="phone"
                        value="{{ $user->phone }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Address
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                        id="address"
                        name="address"
                        rows="3">{{ $user->address }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="profile_picture">
                        Profile Picture
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                        id="profile_picture"
                        type="file"
                        name="profile_picture">
                    @if($user->profile_picture)
                    <p class="text-xs text-gray-500 mt-1">Current: {{ basename($user->profile_picture) }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        New Password (leave blank to keep current)
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                        id="password"
                        type="password"
                        name="password">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                        Confirm New Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelEditBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-[#393646] hover:bg-[#4A4458] text-white font-bold py-2 px-4 rounded">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-[#6D5D6E] text-white text-center py-4 w-full mt-auto">
        Dibuat dengan 💙 oleh © 2025 Sipanang
    </footer>

    <script>
        // Modal edit profil staff kampus
        const editProfileBtn = document.getElementById('editProfileBtn');
        const editProfileModal = document.getElementById('editProfileModal');
        const cancelEditBtn = document.getElementById('cancelEditBtn');

        editProfileBtn.addEventListener('click', () => {
            editProfileModal.classList.remove('hidden');
        });

        cancelEditBtn.addEventListener('click', () => {
            editProfileModal.classList.add('hidden');
        });

        // Close modal if clicking outside the modal content
        editProfileModal.addEventListener('click', (event) => {
            if (event.target === editProfileModal) {
                editProfileModal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
