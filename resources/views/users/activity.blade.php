<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Activity</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Playpen+Sans:wght@300;500&display=swap" rel="stylesheet" />
    <!-- Icon -->
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-[Lato] h-screen">
    <!-- Header Section -->
    <header class="bg-white">
        <!-- Header Navigation -->
        <nav class="flex justify-between items-center w-[90%] xl:w-[70%] mx-auto">
            <!-- Logo -->
            <div>
                <img class="mb-3 mt-3 h-[4rem] sm:h-20 cursor-pointer" src="Assets/img/lostnfoundlogo.png" alt="Logo">
            </div>

            <!-- Notification and Profile Section -->
            <div class="flex items-center gap-6 relative">
                <!-- Notification Button -->
                <button id="notification-icon" type="button" class="relative text-[#124076] p-0 w-full h-full items-center rounded-[20%]">
                    <i class="bx bxs-bell text-3xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-2">
                        0
                    </span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notification-dropdown" class="absolute top-full mt-2 right-0 w-80 bg-white shadow-lg rounded-lg hidden z-20">
                    <ul class="divide-y divide-gray-200">
                        <li class="p-4 text-center text-gray-500">Tidak ada pemberitahuan baru</li>
                    </ul>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="menuDropdownButton" data-dropdown-toggle="menuDropdown"
                        class="flex items-center justify-between text-sm font-medium text-gray-900 rounded-full hover:text-blue-600"
                        type="button">
                        <img class="w-8 h-8 rounded-full mr-2"
                            src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('Assets/img/default-avatar.png') }}"
                            alt="User Avatar">
                        <span class="whitespace-nowrap">{{ $user->first_name }} {{ $user->last_name }}</span>
                        <i class='bx bx-chevron-down text-gray-500 ml-2 text-lg'></i>
                    </button>

                    <!-- Profile Dropdown Menu -->
                    <div id="menuDropdown" class="hidden absolute top-full mt-2 right-0 bg-white divide-y divide-gray-100 rounded-lg shadow-md w-44">
                        <ul class="py-1 text-sm text-gray-700">
                            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Home</a></li>
                            <li><a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
                            <li><a href="{{ route('activity') }}" class="block px-4 py-2 hover:bg-gray-100">Activity</a></li>
                            <li><a href="{{ route('about-us') }}" class="block px-4 py-2 hover:bg-gray-100">About Us</a></li>
                        </ul>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Activity Section -->
    <div class="bg-[#91B0D3] min-h-screen py-10">
        <!-- My Activity Section -->
        <!-- Di bagian atas section -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 w-[50%] mx-auto">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
        @endif
        <section class="max-w-5xl mx-auto bg-white rounded-md shadow-md p-8">
            <h2 class="text-3xl font-semibold mb-6">Aktivitas Saya</h2>

            <!-- Filter Section -->
            <div class="mb-6">
                <form method="GET" action="activity.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Dropdown Kategori -->
                    <div>
                        <label for="category" class="block mb-2">Kategori:</label>
                        <select id="category" name="category" class="w-full border rounded p-2">
                            <option value="">Semua</option>
                            <option value="Perhiasan Khusus">Perhiasan Khusus</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Buku & Dokumen">Buku & Dokumen</option>
                            <option value="Aksesoris Pribadi">Aksesoris Pribadi</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Perangkat Medis">Perangkat Medis</option>
                        </select>
                    </div>

                    <!-- Dropdown Jenis Laporan -->
                    <div>
                        <label for="type" class="block mb-2">Jenis Laporan:</label>
                        <select id="type" name="type" class="w-full border rounded p-2">
                            <option value="">Semua</option>
                            <option value="hilang">Hilang</option>
                            <option value="ditemukan">Ditemukan</option>
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="md:col-span-2 text-right">
                        <button type="submit" class="bg-[#004274] text-white px-4 py-2 rounded">Terapkan Filter</button>
                        <a href="activity.php" class="bg-gray-500 text-white px-4 py-[0.68rem] rounded">Reset Filter</a>
                    </div>
                </form>
            </div>

            <!-- Tab Navigation - Tambahkan setelah filter section -->
            <div class="border-b border-gray-200 mb-6">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="#" class="inline-block p-4 border-b-2 border-blue-600 text-blue-600 active"
                            onclick="showTab('tab-content-1', this); return false;">Barang Saya</a>
                    </li>
                    <li class="mr-2">
                        <a href="#" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            onclick="showTab('tab-content-2', this); return false;">Aktivitas Pada Barang Saya</a>
                    </li>
                    <li class="mr-2">
                        <a href="#" class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300"
                            onclick="showTab('tab-content-3', this); return false;">Pengajuan Saya</a>
                    </li>
                </ul>
            </div>

            <!-- Tab content 1: Barang Saya -->
            <div id="tab-content-1">
                @if(count($userItems) > 0)
                @foreach($userItems as $item)
                <div class="flex items-start justify-between bg-blue-50 p-4 rounded-md mb-4">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $item->photo_path ? asset('storage/' . $item->photo_path) : asset('Assets/img/no-image.png') }}"
                            alt="{{ $item->item_name }}"
                            class="w-24 h-24 object-cover rounded" />
                        <div>
                            <h3 class="text-xl font-medium">{{ $item->item_name }}</h3>
                            <p class="text-sm text-gray-500">Tanggal Kejadian: {{ $item->date_of_event }}</p>
                            <p class="text-sm text-gray-500">Dibuat pada: {{ $item->created_at->format('d M Y') }}</p>

                            <!-- Status Badge -->
                            <div class="mt-1">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $item->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                  ($item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <!-- Tombol Detail/Edit Laporan -->
                            <button
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->item_name }}"
                                data-category="{{ $item->category }}"
                                data-type="{{ $item->type }}"
                                data-date="{{ \Carbon\Carbon::parse($item->date_of_event)->format('Y-m-d') }}"
                                data-description="{{ $item->description }}"
                                data-email="{{ $item->email ?? '' }}"
                                data-phone="{{ $item->phone_number ?? '' }}"
                                data-image="{{ $item->photo_path ? asset('storage/' . $item->photo_path) : asset('Assets/img/no-image.png') }}"
                                onclick="openModalFromData(this)"
                                class="mt-2 mr-4 bg-[#004274] text-white py-1 px-4 rounded">
                                Edit Laporan
                            </button>

                            <!-- Tombol Hapus -->
                            <a href="javascript:void(0);" class="mt-2 bg-red-100 text-red-700 py-2 px-4 rounded hover:bg-red-200"
                                onclick="confirmDelete('{{ $item->id }}')">Hapus Laporan</a>
                        </div>
                    </div>

                    <!-- Bagian label jenis laporan -->
                    <div class="flex flex-col space-y-2 ml-4">
                        <span class="bg-{{ $item->type == 'hilang' ? 'red' : 'green' }}-100 text-{{ $item->type == 'hilang' ? 'red' : 'green' }}-700 px-3 py-1 rounded-md text-sm text-center">
                            {{ $item->type }}
                        </span>
                    </div>
                </div>
                @endforeach
                @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Tidak ada laporan yang ditemukan.</p>
                    <a href="{{ route('formReport') }}" class="inline-block mt-4 bg-[#124076] text-white px-6 py-2 rounded-lg">Buat Laporan Baru</a>
                </div>
                @endif
            </div>


            <!-- Tab content 2: Aktivitas Pada Barang Saya -->
            <div id="tab-content-2" class="hidden">
                <h3 class="text-xl font-semibold mb-4">Permintaan Pengembalian & Klaim Barang Anda</h3>

                @if(isset($claimsOnMyItems) && $claimsOnMyItems->count() > 0)
                <div class="space-y-4">
                    @foreach($claimsOnMyItems as $claim)
                    <div class="bg-white p-4 rounded-lg shadow border-l-4 {{ $claim->type == 'return' ? 'border-purple-500' : 'border-blue-500' }}">
                        <div class="flex justify-between">
                            <div>
                                <span class="inline-block px-2 py-1 text-xs {{ $claim->type == 'return' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} rounded-full mb-2">
                                    {{ $claim->type == 'return' ? 'Pengembalian' : 'Klaim' }}
                                </span>
                                <h4 class="font-semibold">{{ $claim->item->item_name }}</h4>
                                <p class="text-sm text-gray-600">
                                    Oleh: {{ $claim->claimer_name }} ({{ $claim->claimer_phone }})
                                </p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($claim->claim_date)->format('d M Y H:i') }}</p>

                                @if($claim->type == 'return')
                                <p class="text-sm mt-2">
                                    <strong>Lokasi ditemukan:</strong> {{ $claim->where_found }}
                                </p>
                                <p class="text-sm">
                                    <strong>Catatan:</strong> {{ $claim->notes ?? 'Tidak ada catatan' }}
                                </p>
                                @if($claim->item_photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$claim->item_photo) }}" alt="Foto Barang" class="w-24 h-24 object-cover rounded">
                                </div>
                                @endif
                                @endif
                            </div>

                            <div>
                                @if($claim->status == 'pending')
                                <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Menunggu</span>

                                <div class="mt-2 space-x-2">
                                    <form action="{{ route('claim.update-status') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="claim_id" value="{{ $claim->id }}">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="bg-green-500 text-white text-xs px-2 py-1 rounded hover:bg-green-600">
                                            {{ $claim->type == 'return' ? 'Terima Pengembalian' : 'Terima Klaim' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('claim.update-status') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="claim_id" value="{{ $claim->id }}">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                                @elseif($claim->status == 'approved')
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Diterima</span>
                                @elseif($claim->status == 'rejected')
                                <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
                    Belum ada aktivitas pada barang Anda
                </div>
                @endif
            </div>

            <!-- Tab content 3: Pengajuan Saya -->
            <div id="tab-content-3" class="hidden">
                <h3 class="text-xl font-semibold mb-4">Klaim & Pengembalian yang Saya Ajukan</h3>

                @if(isset($myClaimsAndReturns) && $myClaimsAndReturns->count() > 0)
                <div class="space-y-4">
                    @foreach($myClaimsAndReturns as $myClaim)
                    <div class="bg-white p-4 rounded-lg shadow border-l-4 {{ $myClaim->type == 'return' ? 'border-purple-500' : 'border-blue-500' }}">
                        <div class="flex justify-between">
                            <div>
                                <span class="inline-block px-2 py-1 text-xs {{ $myClaim->type == 'return' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} rounded-full mb-2">
                                    {{ $myClaim->type == 'return' ? 'Pengembalian' : 'Klaim' }}
                                </span>
                                <h4 class="font-semibold">{{ $myClaim->item->item_name }}</h4>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($myClaim->claim_date)->format('d M Y H:i') }}</p>

                                <div class="mt-2">
                                    <span class="inline-block px-2 py-1 
                                    {{ $myClaim->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($myClaim->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }} 
                                    text-xs rounded-full">
                                        {{ ucfirst($myClaim->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
                    Anda belum melakukan klaim atau pengembalian apapun
                </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Modal Detail -->
    <!-- Modal Edit -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <form method="POST" id="updateItemForm" class="bg-white rounded-lg w-[90%] md:w-[50%] p-6 max-h-[90vh] overflow-y-auto" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Edit Laporan</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <!-- Content -->
            <div class="mt-4 space-y-4">
                <!-- Hidden ID -->
                <input type="hidden" id="modalItemId" name="id" value="">

                <!-- Gambar Barang -->
                <div class="text-center">
                    <img id="modalImage" src="" alt="Gambar Barang" class="w-32 h-32 object-cover mx-auto rounded mb-2">
                    <label class="block text-sm font-medium text-gray-700 mt-2"></label>
                    <input type="file" name="photo" id="modalPhotoInput" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                    file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
                </div>

                <div>
                    <label for="modalNameInput" class="block mb-2">Nama Item:</label>
                    <input type="text" id="modalNameInput" name="item_name" class="w-full border rounded p-2">
                </div>

                <!-- Input Kategori -->
                <div>
                    <label for="modalCategoryInput" class="block mb-2">Kategori:</label>
                    <select id="modalCategoryInput" name="category" class="w-full border rounded p-2">
                        <option value="Perhiasan Khusus">Perhiasan Khusus</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Buku & Dokumen">Buku & Dokumen</option>
                        <option value="Aksesoris Pribadi">Aksesoris Pribadi</option>
                        <option value="Kendaraan">Kendaraan</option>
                        <option value="Perangkat Medis">Perangkat Medis</option>
                    </select>
                </div>

                <!-- Input Jenis Laporan -->
                <div>
                    <label for="modalTypeInput" class="block mb-2">Jenis Laporan:</label>
                    <select id="modalTypeInput" name="type" class="w-full border rounded p-2">
                        <option value="hilang">Hilang</option>
                        <option value="ditemukan">Ditemukan</option>
                    </select>
                </div>

                <!-- Input Email -->
                <div>
                    <label for="modalEmailInput" class="block mb-2">Email:</label>
                    <input type="email" id="modalEmailInput" name="email" class="w-full border rounded p-2">
                </div>

                <!-- Input Nomor Telepon -->
                <div>
                    <label for="modalPhoneInput" class="block mb-2">Nomor Telepon:</label>
                    <input type="text" id="modalPhoneInput" name="phone_number" class="w-full border rounded p-2">
                </div>

                <!-- Input Tanggal Kejadian -->
                <div>
                    <label for="modalDateInput" class="block mb-2">Tanggal Kejadian:</label>
                    <input type="date" id="modalDateInput" name="date_of_event" class="w-full border rounded p-2">
                </div>

                <!-- Input Deskripsi -->
                <div>
                    <label for="modalDescriptionInput" class="block mb-2">Deskripsi:</label>
                    <textarea id="modalDescriptionInput" name="description" class="w-full border rounded p-2"></textarea>
                </div>
            </div>
            <!-- Footer -->
            <div class="mt-6 text-right">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700" onclick="closeModal()">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Footer Section -->
    <footer class="bg-[#124076]">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="flex items-center">
                        <img src="Assets/img/lostnfoundlogowhite.png" class="h-28 me-3" alt="FlowBite Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-white uppercase dark:text-white">About</h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4"><a href="about-us-non-log.html" class="hover:underline">About Lost and Found Items</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Lost and Found Items</h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4"><a href="#" class="hover:underline">Lost Items</a></li>
                            <li><a href="#" class="hover:underline">Found Items</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4"><a href="about-us.php" class="hover:underline">Feedback</a></li>
                            <li><a href="terms-condition.html" class="hover:underline">Terms & Conditions Lost and Found Items</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-white sm:mx-auto" />
            <div class="text-center sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-white text-center">© 2024 <a href="terms-condition.html" class="hover:underline">Lost and Found Team</a>. All Rights Reserved.</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

    <script>
        function closeModal() {
            document.getElementById("detailModal").classList.add("hidden");
        }
    </script>

    <script>
        const navLinks = document.querySelector(".nav-links");

        function onToggleMenu(e) {
            e.name = e.name === "menu" ? "close" : "menu";
            navLinks.classList.toggle("top-[11%]");
        }
    </script>


    <script>
        function confirmDelete(itemId) {
            Swal.fire({
                title: "Ingin menghapus laporan ini?",
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#124076",
                cancelButtonColor: "#6e7881",
                cancelButtonText: "Batalkan",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form for proper DELETE method submission
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/items/' + itemId;

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Add method DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    // Submit the form
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        document.getElementById("notification-icon").addEventListener("click", function() {
            const dropdown = document.getElementById("notification-dropdown");
            dropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("notification-dropdown");
            const button = document.getElementById("notification-icon");
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    </script>

    <script>
        function openModalFromData(element) {
            const id = element.getAttribute('data-id');
            const name = element.getAttribute('data-name') || '';
            const category = element.getAttribute('data-category') || '';
            const type = element.getAttribute('data-type') || '';
            const date = element.getAttribute('data-date') || '';
            const description = element.getAttribute('data-description') || '';
            const email = element.getAttribute('data-email') || '';
            const phone = element.getAttribute('data-phone') || '';
            const image = element.getAttribute('data-image') || '';

            // Log to console for debugging
            console.log('Opening modal with data:', {
                id,
                name,
                category,
                type,
                date,
                description,
                email,
                phone,
                image
            });

            // Set the form action URL
            const form = document.getElementById("updateItemForm");
            form.action = "/items/" + id;

            // Populate form fields
            document.getElementById("modalItemId").value = id;
            document.getElementById("modalNameInput").value = name; // Make sure this field exists
            document.getElementById("modalCategoryInput").value = category;
            document.getElementById("modalTypeInput").value = type;
            document.getElementById("modalDateInput").value = date;
            document.getElementById("modalDescriptionInput").value = description;
            document.getElementById("modalEmailInput").value = email;
            document.getElementById("modalPhoneInput").value = phone;
            document.getElementById("modalImage").src = image;

            document.getElementById("detailModal").classList.remove("hidden");
        }

        document.querySelector('#detailModal button.text-gray-500').addEventListener('click', closeModal);
        document.querySelector('#detailModal button.bg-gray-500').addEventListener('click', closeModal);
    </script>

    <script>
        document.getElementById("menuDropdownButton").addEventListener("click", function(event) {
            event.stopPropagation();
            const dropdown = document.getElementById("menuDropdown");
            dropdown.classList.toggle("hidden");
        });


        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("menuDropdown");
            const button = document.getElementById("menuDropdownButton");
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    </script>

    <script>
        function showTab(tabId, clickedTab) {
            // Hide all tab contents
            document.querySelectorAll('[id^="tab-content-"]').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Show the selected tab content
            document.getElementById(tabId).classList.remove('hidden');

            // Update active tab styling
            document.querySelectorAll('.border-b-2').forEach(tab => {
                tab.classList.remove('border-blue-600', 'text-blue-600');
                tab.classList.add('border-transparent');
            });

            clickedTab.classList.remove('border-transparent');
            clickedTab.classList.add('border-blue-600', 'text-blue-600');
        }
    </script>
</body>

</html>