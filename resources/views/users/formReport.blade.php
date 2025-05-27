<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Playpen+Sans:wght@300;500&display=swap"
        rel="stylesheet" />
    <!-- Tailwind JS -->
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <!-- bx bx-icons -->
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet" />
    <!-- flowbite -->
    <link
        href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"
        rel="stylesheet" />
    <!-- Tambahkan di bagian <head> -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <title>Form Report</title>
</head>

<!-- Header Section -->

<body class="font-[Lato] h-screen">
    <header class="bg-white">
        <!-- Header -->
        <nav class="flex justify-between items-center w-[90%] xl:w-[70%] mx-auto">
            <!-- Logo -->
            <div>
                <img class="mb-3 mt-3 h-[4rem] sm:h-20 cursor-pointer" src="Assets/img/lostnfoundlogo.png" alt="Logo">
            </div>

            <!-- Kontainer Notifikasi dan Profile -->
            <div class="flex items-center gap-6">
                <!-- Tombol Notifikasi -->
                <div class="relative">
                    <button id="notification-icon" type="button" class="relative text-[#124076] p-0 w-full h-full items-center rounded-[20%]">
                        <i class="bx bxs-bell text-3xl"></i>
                        <!-- Badge -->
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div id="notification-dropdown" class="absolute top-full mt-2 right-0 w-80 bg-white shadow-lg rounded-lg hidden z-20">
                        <ul class="divide-y divide-gray-200">
                            <li class="p-4 text-center text-gray-500">Tidak ada pemberitahuan baru</li>
                        </ul>
                    </div>
                </div>

                <!-- Dropdown Profile -->
                <div class="relative">
                    <button id="menuDropdownButton" data-dropdown-toggle="menuDropdown"
                        class="flex items-center text-sm font-medium text-gray-900 rounded-full hover:text-blue-600"
                        type="button">
                        <img class="w-8 h-8 rounded-full mr-2"
                            src="{{ auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : asset('Assets/img/default-avatar.png') }}"
                            alt="User Avatar">
                        <span class="truncate">{{ $user->first_name }} {{ $user->last_name }}</span>
                        <i class='bx bx-chevron-down text-gray-500 ml-2 text-lg'></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="menuDropdown"
                        class="hidden absolute top-full mt-2 right-0 bg-white divide-y divide-gray-100 rounded-lg shadow-md w-44">
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
    <!-- Header Section -->

    <!-- Form Section -->
    <section class="bg-[#9BB3CD]">
        <main class="flex justify-center items-center py-12">
            <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-4xl">
                <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Type Section -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Apakah barang ini hilang atau ditemukan?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="hilang" class="hidden peer" required>
                                <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg peer-checked:bg-blue-500 peer-checked:text-white cursor-pointer">Hilang</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="ditemukan" class="hidden peer" required>
                                <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg peer-checked:bg-blue-500 peer-checked:text-white cursor-pointer">Ditemukan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Mengambil foto barang tersebut</label>
                            <div class="border-dashed border-2 border-gray-300 rounded-lg p-6 flex items-center justify-center">
                                <label class="cursor-pointer text-blue-500 hover:underline">
                                    <!-- Tambahkan id="photo" -->
                                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden">
                                    Click to Upload or Drag and Drop <br>
                                    <span class="text-sm text-gray-500">Max. File size: 15MB</span>
                                </label>
                            </div>
                            <!-- Tambahkan elemen img untuk preview gambar -->
                            <img id="photo-preview" src="#" alt="Photo Preview" class="hidden mt-4 w-32 h-32 object-cover rounded-lg" />
                        </div>

                        <!-- Item Info Section -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama barang</label>
                            <input type="text" name="item_name" placeholder="Masukan Nama Barang Anda" class="w-full border rounded-lg p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Kategori Barang</label>
                            <div class="flex flex-wrap gap-2">
                                <select name="category" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">All Categories</option>
                                    <option value="Alat Tulis">Perhiasan Khusus</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Buku & Dokumen">Buku & Dokumen</option>
                                    <option value="Tas & Dompet">Tas & Dompet</option>
                                    <option value="Perlengkapan Pribadi">Perlengkapan Pribadi</option>
                                    <option value="Peralatan Praktikum">Peralatan Praktikum</option>
                                    <option value="Aksesori">Aksesori</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- Date Section -->
                    <div class="mt-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kehilangan/Menemukan</label>
                        <input type="date" name="date_of_event" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <!-- Description Section -->
                    <div class="mt-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea name="description" placeholder="Berikan detail tentang item tersebut" class="w-full border rounded-lg p-2 h-28 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>

                    <!-- Contact Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Informasi Kontak</label>
                            <div class="flex items-center border rounded-lg p-2 mb-4 focus-within:ring-2 focus-within:ring-blue-500">
                                <i class='bx bxs-envelope text-[#004274] text-lg'></i>
                                <input type="email" name="email" value="{{ $user->email }}"
                                    class="w-full border-none focus:outline-none px-2 bg-gray-50"
                                    readonly required>
                            </div>
                            <div class="flex items-center border rounded-lg p-2 mb-4 focus-within:ring-2 focus-within:ring-blue-500">
                                <i class='bx bxl-whatsapp text-green-500 text-lg'></i>
                                <input type="text" name="phone_number" value="{{ $user->phone ?? '' }}"
                                    class="w-full border-none focus:outline-none px-2 bg-gray-50"
                                    readonly required>
                            </div>
                        </div>

                        <!-- Map Section -->
                        <div class="mt-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sematkan Lokasi</label>
                            <div id="map" class="w-full h-64 bg-gray-200 rounded-lg"></div>
                            <!-- Input tersembunyi untuk koordinat -->
                            <input type="hidden" name="location" id="location-coordinates" value="-6.973250, 107.630339">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 text-center">
                        <button id="submitReport" type="submit" class="bg-[#124076] text-white px-6 py-2 rounded-lg">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </section>
    <!-- End of Form Section -->



    <!-- Footer Section -->

    <footer class="bg-[#124076]">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="flex items-center">
                        <img
                            src="Assets/img/lostnfoundlogowhite.png"
                            class="h-28 me-3"
                            alt="FlowBite Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2
                            class="mb-6 text-sm font-semibold text-white uppercase dark:text-white">
                            About
                        </h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4">
                                <a href="https://flowbite.com/" class="hover:underline">About Lost and Found Items</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2
                            class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
                            Lost and Found Items
                        </h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4">
                                <a
                                    href="https://github.com/themesberg/flowbite"
                                    class="hover:underline">Lost Items</a>
                            </li>
                            <li>
                                <a
                                    href="https://discord.gg/4eeurUVvTy"
                                    class="hover:underline">Found Items</a>
                            </li>
                            <li>
                                <a
                                    href="https://discord.gg/4eeurUVvTy"
                                    class="hover:underline">Information about Lost and Found Items</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2
                            class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
                            Legal
                        </h2>
                        <ul class="text-white font-medium">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Feedback</a>
                            </li>
                            <li>
                                <a href="#" class="hover:underline">Terms &amp; Conditions Lost and Found Items</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-white sm:mx-auto" />
            <div class="text-center sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-white text-center">© 2024 <a href="/" class="hover:underline">Lost and Found Team</a>.
                    All Rights Reserved.
                </span>
            </div>
        </div>
    </footer>

    <!-- Script JS  -->

    <script>
        const navLinks = document.querySelector(".nav-links");

        function onToggleMenu(e) {
            e.name = e.name === "menu" ? "close" : "menu";
            navLinks.classList.toggle("top-[11%]");
        }
    </script>

    <script>
        document.addEventListener("click", (e) => {
            // Dropdown 1
            const dropdownMenu1 = document.getElementById("dropdownMenu1");
            const dropdownButton1 = document.querySelector(
                'button[onclick="toggleDropdown1()"]'
            );

            if (
                !dropdownButton1.contains(e.target) &&
                !dropdownMenu1.contains(e.target)
            ) {
                dropdownMenu1.classList.add("hidden");
            }

            // Dropdown 2
            const dropdownMenu2 = document.getElementById("dropdownMenu2");
            const dropdownButton2 = document.querySelector(
                'button[onclick="toggleDropdown2()"]'
            );

            if (
                !dropdownButton2.contains(e.target) &&
                !dropdownMenu2.contains(e.target)
            ) {
                dropdownMenu2.classList.add("hidden");
            }
        });

        function toggleDropdown1() {
            const dropdownMenu1 = document.getElementById("dropdownMenu1");
            dropdownMenu1.classList.toggle("hidden");
        }

        function toggleDropdown2() {
            const dropdownMenu2 = document.getElementById("dropdownMenu2");
            dropdownMenu2.classList.toggle("hidden");
        }
    </script>
    <!-- Maps Pin -->
    <script>
        // Koordinat Telkom University, Bandung
        const telkomUniversityCoords = [-6.973250, 107.630339];

        // Inisialisasi peta
        const map = L.map('map').setView(telkomUniversityCoords, 16); // Zoom level 16 untuk lebih detail

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker di Telkom University
        const marker = L.marker(telkomUniversityCoords, {
            draggable: true
        }).addTo(map);

        // Perbarui koordinat saat marker dipindahkan
        marker.on('moveend', (e) => {
            const {
                lat,
                lng
            } = e.target.getLatLng();
            console.log(`Latitude: ${lat}, Longitude: ${lng}`);
            document.getElementById('location-coordinates').value = `${lat}, ${lng}`;
        });
    </script>

    <!-- Report Link -->
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownContent');
            dropdown.classList.toggle('hidden'); // Menampilkan atau menyembunyikan dropdown
        }
    </script>

    <!-- Tambahkan skrip ini di bagian bawah sebelum </body> -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photo-preview');

            photoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    // Periksa apakah file adalah gambar
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                            photoPreview.classList.remove('hidden');
                        }

                        reader.readAsDataURL(file);
                    } else {
                        alert('Please upload a valid image file.');
                        photoInput.value = ''; // Reset input file
                        photoPreview.src = '#';
                        photoPreview.classList.add('hidden');
                    }
                } else {
                    // Jika tidak ada file yang dipilih
                    photoPreview.src = '#';
                    photoPreview.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('submitReport').addEventListener('click', function(event) {
                event.preventDefault();


                const form = document.querySelector('form[action="{{ route("report.store") }}"]');


                if (!form) {
                    console.error('Form tidak ditemukan');
                    return;
                }


                const csrfToken = form.querySelector('input[name="_token"]');
                if (!csrfToken) {
                    console.error('CSRF token tidak ditemukan');
                    return;
                }


                let isValid = true;
                const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });


                if (!isValid) {
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Tolong isi semua field yang wajib diisi.',
                        icon: 'warning',
                        confirmButtonColor: '#004274',
                    });
                    return;
                }


                Swal.fire({
                    title: 'Kirim Laporan?',
                    text: "Apakah Anda yakin ingin mengirimkan laporan ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#124076',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kirim!',
                    cancelButtonText: 'Batalkan',
                }).then((result) => {
                    if (result.isConfirmed) {

                        console.log('Submitting form to:', form.action);

                        try {

                            form.submit();
                        } catch (error) {
                            console.error('Error submitting form:', error);
                        }
                    }
                });
            });
        });
    </script>

    <!-- Flowbite -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>