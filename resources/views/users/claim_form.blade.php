<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Klaim Barang Hilang</title>
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
</head>

<body class="font-[Lato] h-screen">
    <!-- Header Section -->
    <header class="bg-white">
        <!-- Header Navigation -->
        <nav class="flex justify-between items-center w-[90%] xl:w-[70%] mx-auto">
            <!-- Logo -->
            <div>
                <a href="{{ route('dashboard') }}">
                    <img class="mb-3 mt-3 h-[4rem] sm:h-20 cursor-pointer" src="{{ asset('Assets/img/lostnfoundlogo.png') }}" alt="Logo">
                </a>
            </div>

            <!-- Notification and Profile Section -->
            @if(Auth::check())
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
                            src="{{ Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : asset('Assets/img/default-avatar.png') }}"
                            alt="User Avatar">
                        <span class="whitespace-nowrap">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
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
            @else
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-[#124076] hover:text-blue-800">Login</a>
                <a href="{{ route('register') }}" class="bg-[#124076] text-white px-4 py-2 rounded hover:bg-blue-800">Register</a>
            </div>
            @endif
        </nav>
    </header>

    <!-- Claim Form Section -->
    <div class="bg-[#91B0D3] min-h-screen py-10">
        <!-- Claim Form -->
        <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-[#124076]">Klaim Barang Ditemukan</h2>
                <p class="text-gray-600 mt-2">Silakan isi formulir di bawah ini untuk mengklaim barang yang telah ditemukan</p>
            </div>

            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('items.claim.store', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <!-- Detail Barang Section -->
                <div class="bg-gray-50 p-4 rounded-md mb-6">
                    <h3 class="font-semibold text-lg mb-2 text-[#124076]">Detail Barang</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Barang:</p>
                            <p class="font-medium">{{ $item->item_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kategori:</p>
                            <p class="font-medium">{{ $item->category }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Ditemukan:</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($item->date_of_event)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Deskripsi:</p>
                            <p class="font-medium">{{ $item->description }}</p>
                        </div>
                    </div>
                    @if($item->photo_path)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 mb-2">Foto Barang:</p>
                        <img src="{{ asset('storage/' . $item->photo_path) }}" alt="{{ $item->item_name }}" class="w-32 h-32 object-cover rounded-md">
                    </div>
                    @endif
                </div>

                <!-- Informasi Klaim -->
                <div>
                    <h3 class="font-semibold text-lg mb-4 text-[#124076]">Informasi Klaim</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="claimer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                            <input type="text" id="claimer_name" name="claimer_name" value="{{ old('claimer_name', Auth::user()->first_name . ' ' . Auth::user()->last_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        </div>

                        <div>
                            <label for="returner_nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                            <input type="text" id="returner_nim" name="returner_nim" value="{{ Auth::user()->nim }}" class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="claimer_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-600">*</span></label>
                            <input type="email" id="claimer_email" name="claimer_email" value="{{ old('claimer_email', Auth::user()->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="claimer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-600">*</span></label>
                            <input type="text" id="claimer_phone" name="claimer_phone" value="{{ old('claimer_phone', Auth::user()->phone ?? '') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        </div>

                        <!-- Tanggal Klaim -->
                        <div>
                            <label for="claim_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Klaim</label>
                            <input type="date" id="claim_date" name="claim_date" value="{{ old('claim_date', date('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" readonly>
                        </div>
                    </div>

                    <!-- Bukti Kepemilikan -->
                    <div class="mt-6">
                        <label for="ownership_proof" class="block text-sm font-medium text-gray-700 mb-1">Bukti Kepemilikan <span class="text-red-600">*</span></label>
                        <textarea id="ownership_proof" name="ownership_proof" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Jelaskan secara detail bukti kepemilikan Anda (misalnya: ciri khusus barang, tanggal pembelian, nota pembelian, dll)" required>{{ old('ownership_proof') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Semakin detail bukti yang Anda berikan, semakin tinggi kemungkinan klaim Anda diterima.</p>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Informasi tambahan yang ingin Anda sampaikan (opsional)">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Upload Dokumen Bukti -->
                    <div class="mt-6">
                        <label for="proof_document" class="block text-sm font-medium text-gray-700 mb-1">Dokumen Bukti (Opsional)</label>
                        <input type="file" id="proof_document" name="proof_document" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-sm text-gray-500 mt-1">Unggah dokumen bukti kepemilikan (receipt, invoice, foto barang saat masih dimiliki, dll) - Maks. 2MB (PDF, JPG, PNG)</p>
                    </div>
                </div>

                <!-- Terms & Submit -->
                <div class="mt-8">
                    <div class="flex items-start mb-4">
                        <input type="checkbox" id="terms" name="terms" class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            Saya menyatakan bahwa informasi yang saya berikan adalah benar dan saya berhak mengklaim barang ini sebagai pemilik yang sah.
                        </label>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-800">
                            <i class="bx bx-arrow-back mr-1"></i> Kembali
                        </a>
                        <button type="submit" class="bg-[#124076] text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Ajukan Klaim
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-[#124076]">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="flex items-center">
                        <img src="{{ asset('Assets/img/lostnfoundlogowhite.png') }}" class="h-28 me-3" alt="Lost and Found Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-white uppercase">About</h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4"><a href="{{ route('about-us') }}" class="hover:underline">About Lost and Found Items</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-white uppercase">Lost and Found Items</h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4"><a href="#" class="hover:underline">Lost Items</a></li>
                            <li><a href="#" class="hover:underline">Found Items</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-white uppercase">Legal</h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4"><a href="{{ route('about-us') }}" class="hover:underline">Feedback</a></li>
                            <li><a href="#" class="hover:underline">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-white sm:mx-auto" />
            <div class="text-center">
                <span class="text-sm text-white">© 2025 <a href="#" class="hover:underline">Lost and Found Team</a>. All Rights Reserved.</span>
            </div>
        </div>
    </footer>

    <script>
        // Notification dropdown toggle
        document.getElementById("notification-icon").addEventListener("click", function() {
            const dropdown = document.getElementById("notification-dropdown");
            dropdown.classList.toggle("hidden");
        });

        // Close notification dropdown when clicking outside
        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("notification-dropdown");
            const button = document.getElementById("notification-icon");
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });

        // Profile dropdown toggle
        document.getElementById("menuDropdownButton").addEventListener("click", function(event) {
            event.stopPropagation();
            const dropdown = document.getElementById("menuDropdown");
            dropdown.classList.toggle("hidden");
        });

        // Close profile dropdown when clicking outside
        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("menuDropdown");
            const button = document.getElementById("menuDropdownButton");
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add("hidden");
            }
        });
    </script>
</body>

</html>