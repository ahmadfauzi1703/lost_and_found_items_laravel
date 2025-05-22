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
    href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&family=Playpen+Sans:wght@300;500&display=swap"
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Home - Dashboard</title>
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
              src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('Assets/img/default-avatar.png') }}"
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

    <!-- Announcement -->
    <div class="text-center">
      <p class="text-sm md:text-lg text-black bg-blue-100">
        Kehilangan atau Menemukan Barang? Laporkan Sekarang Melalui Form Ini! <a class="underline text-[#124076] font-semibold ml-2" href="{{ route('formReport') }}">Klik Disini</a>
      </p>
    </div>
  </header>

  <!-- Search Section -->
  <section class="bg-[#91B0D3] h-[15rem] flex flex-col items-center justify-center">
    <!-- Dropdown Buttons and Search -->
    <div class="w-full max-w-4xl px-4">
      <form class="flex flex-col sm:flex-row items-center gap-4 w-full" method="GET" action="">
        <!-- Dropdown (Select) -->
        <div class="relative w-full sm:w-auto">
          <select
            id="category-dropdown"
            name="category"
            class="w-full sm:w-auto flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-black bg-gray-100 border border-gray-300 rounded-lg sm:rounded-l-lg sm:rounded-r-none focus:ring-2 focus:outline-none focus:ring-blue-500">
            <option value="all">Semua Kategori</option>
            <option value="Perhiasan Khusus">Perhiasan Khusus</option>
            <option value="Elektronik">Elektronik</option>
            <option value="Buku & Dokumen">Buku & Dokumen</option>
            <option value="Aksesoris Pribadi">Aksesoris Pribadi</option>
            <option value="Kendaraan">Kendaraan</option>
            <option value="Perangkat Medis">Perangkat Medis</option>
          </select>
        </div>

        <!-- Search input and button -->
        <div class="relative w-full">
          <input
            type="search"
            id="search-dropdown"
            name="search"
            class="block p-2.5 w-full z-20 text-sm text-black bg-white rounded-lg sm:rounded-none sm:rounded-r-lg border border-gray-300 focus:text-black focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400"
            placeholder="Search" />
          <button
            type="submit"
            class="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-[#124076] rounded-lg sm:rounded-none sm:rounded-r-lg focus:ring-4 focus:outline-none focus:ring-blue-300">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
            <span class="sr-only">Search</span>
          </button>
        </div>
      </form>
    </div>
  </section>

  <!-- card -->
  <section class="bg-[#91B0D3] h-[60rem] sm:h-[50rem] px-4 sm:px-6 lg:px-8">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
      <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif
    <div class="container mx-auto h-full">
      <!-- Wrapper untuk Keterangan dan Grid -->
      <div class="px-4 sm:px-6 lg:px-8">
        <!-- Display Total Reports -->
        <div class="text-gray-700 text-left text-lg font-medium mb-4">
          Total Laporan: {{ $items->count() }} <!-- Menampilkan jumlah laporan -->
        </div>

        <!-- Pembungkus dengan opsi scroll -->
        <div class="overflow-y-auto h-[44rem] scrollbar-thin scrollbar-thumb-[#124076] scrollbar-track-[#e5e7eb] scrollbar-rounded">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($items as $item)
            @php
            $imagePath = !empty($item->photo_path) ? 'storage/' . $item->photo_path : 'images/default-image.jpg';
            @endphp
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
              <div class="relative">
                <div class="absolute top-2 left-2 
      {{ $item->type === 'ditemukan' ? 'bg-green-500' : 'bg-red-500' }} 
      text-white text-xs uppercase font-semibold px-2 py-1 rounded">
                  {{ $item->type }} <!-- Menampilkan jenis item (hilang/ditemukan) -->
                </div>
                <!-- Cek apakah item memiliki foto dan tampilkan -->
                <img src="{{ asset($imagePath) }}" alt="{{ $item->item_name }}" class="w-full h-48 object-cover" />
              </div>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $item->item_name }}</h3>
                <p class="text-sm text-gray-500 mt-2 flex items-center">
                  <i class="bx bx-calendar-alt mr-1"></i> {{ $item->date_of_event->format('d-m-Y') }} <!-- Menampilkan tanggal event -->
                </p>
                <button
                  class="mt-4 w-full bg-[#124076] text-white text-sm py-2 px-4 rounded hover:bg-[#2e64a1]"
                  data-name="{{ $item->item_name }}"
                  data-date="{{ $item->date_of_event->format('d-m-Y') }}"
                  data-email="{{ $item->email }}"
                  data-category="{{ $item->category }}"
                  data-location="{{ $item->location }}"
                  data-description="{{ $item->description }}"
                  data-image="{{ asset($imagePath) }}"
                  data-reporter="{{ $item->report_by ?: ($item->user ? 'Satpam: ' . $item->user->name : 'Unknown') }}"
                  data-whatsapp="https://wa.me/{{ $item->whatsapp_number }}"
                  data-type="{{ $item->type }}"
                  data-id="{{ $item->id }}">
                  Detail
                </button>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Modal -->
  <div id="itemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex justify-center items-center h-full">
      <div class="bg-white p-6 rounded-lg max-w-2xl w-full relative">
        <!-- Tombol Close -->
        <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none">
          <i class="bx bx-x text-2xl"></i>
        </button>

        <!-- Nama Item -->
        <h2 class="text-xl font-semibold text-gray-800" id="modalItemName">Item Name</h2>

        <div class="mt-4">
          <span class="text-gray-600 font-semibold">Dilaporkan oleh:</span>
          <span id="modalReportedBy">-</span>
        </div>

        <!-- Date -->
        <p class="text-sm text-gray-500 mt-5 flex items-center gap-2">
          <i class='bx bxs-calendar text-lg text-[#124076]'></i>
          <span id="modalItemDate">Date: Event Date</span>
        </p>

        <!-- Email -->
        <p class="text-sm text-gray-500 mt-2 flex items-center gap-2">
          <i class='bx bxs-envelope text-lg text-[#124076]'></i>
          <span id="modalItemEmail">Email: useremail@example.com</span>
        </p>

        <!-- Category -->
        <p class="text-sm text-gray-500 mt-2 flex items-center gap-2">
          <i class='bx bxs-category text-lg text-[#124076]'></i>
          <span id="modalItemCategory">Category: Item Category</span>
        </p>

        <!-- Location -->
        <div class="text-sm text-gray-500 mt-2 flex items-center gap-2">
          <i class='bx bx-current-location text-lg text-[#124076]'></i>
          <span id="modalItemLocation">Location: Item Location</span>
          <!-- Tombol Google Maps -->
          <a id="modalGoogleMapsButton" href="#" target="_blank" class="bg-blue-500 text-white text-xs px-3 py-2 rounded hover:bg-blue-600">
            <i class='bx bxs-map-alt'></i> Lihat di Google Maps
          </a>
        </div>

        <!-- Deskripsi Item -->
        <div class="mt-4 border border-gray-300 rounded-lg p-4 bg-gray-50">
          <h3 class="text-sm font-semibold text-gray-700 mb-2">Description:</h3>
          <p class="text-sm text-gray-600" id="modalItemDescription">Item Description</p>
        </div>

        <!-- Gambar -->
        <div class="mt-4 bg-gray-100 rounded-lg overflow-hidden">
          <img id="modalItemImage" src="" alt="Item Image" class="w-full h-48 object-contain" />
        </div>

        <!-- Kontak -->
        <div class="mt-4 flex items-center gap-2">
          <a id="modalWhatsAppButton" href="#" target="_blank" class="bg-green-500 text-white text-xs px-3 py-2 rounded hover:bg-green-600">
            <i class='bx bxl-whatsapp text-sm'></i> Hubungi via What'sApp
          </a>
          <!-- Tombol Claim/Return yang tampil sesuai jenis laporan -->
          <a id="modalClaimButton" href="#" class="hidden bg-blue-600 text-white text-xs px-3 py-2 rounded hover:bg-blue-700">
            <i class='bx bxs-hand-up text-sm'></i> Claim Barang
          </a>

          <a id="modalReturnButton" href="#" class="hidden bg-purple-600 text-white text-xs px-3 py-2 rounded hover:bg-purple-700">
            <i class='bx bx-arrow-back text-sm'></i> Kembalikan Barang
          </a>
        </div>
      </div>
    </div>
  </div>

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
                <a href="about-us-non-log.html" class="hover:underline">About Lost and Found Items</a>
              </li>
            </ul>
          </div>
          <div>
            <h2
              class="mb-6 text-sm font-semibold text-white uppercase dark:text-white">
              Lost and Found Items
            </h2>
            <ul class="text-white font-medium">
              <li class="mb-4">
                <a
                  href="#"
                  class="hover:underline">Lost Items</a>
              </li>
              <li>
                <a
                  href="#"
                  class="hover:underline">Found Items</a>
              </li>
            </ul>
          </div>
          <div>
            <h2
              class="mb-6 text-sm font-semibold text-white uppercase dark:text-white">
              Legal
            </h2>
            <ul class="text-white font-medium">
              <li class="mb-4">
                <a href="about-us.php" class="hover:underline">Feedback</a>
              </li>
              <li>
                <a href="terms-condition.html" class="hover:underline">Terms &amp; Conditions Lost and Found Items</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <hr class="my-6 border-white sm:mx-auto" />
      <div class="text-center sm:flex sm:items-center sm:justify-between">
        <span class="text-sm text-white text-center">© 2025 <a href="/" class="hover:underline">Lost and Found Team</a>.
          All Rights Reserved.
        </span>
      </div>
    </div>
  </footer>
</body>

</html>

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
<!-- Report Link -->
<script>
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownContent');
    dropdown.classList.toggle('hidden'); // Menampilkan atau menyembunyikan dropdown
  }
</script>

<!-- JavaScript for Modal -->
<script>
  // Ambil semua tombol "Detail"
  const detailButtons = document.querySelectorAll('button[data-name]');

  // Ambil elemen modal
  const modal = document.getElementById('itemModal');
  const modalItemName = document.getElementById('modalItemName');
  const modalItemDate = document.getElementById('modalItemDate');
  const modalItemEmail = document.getElementById('modalItemEmail');
  const modalItemCategory = document.getElementById('modalItemCategory');
  const modalItemLocation = document.getElementById('modalItemLocation');
  const modalItemDescription = document.getElementById('modalItemDescription');
  const modalItemImage = document.getElementById('modalItemImage');
  const modalWhatsAppButton = document.getElementById('modalWhatsAppButton');
  const modalGoogleMapsButton = document.getElementById('modalGoogleMapsButton');
  // Tambahkan referensi untuk tombol claim dan return
  const modalClaimButton = document.getElementById('modalClaimButton');
  const modalReturnButton = document.getElementById('modalReturnButton');

  // Tambahkan event listener ke setiap tombol
  detailButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Ambil data dari atribut data-*
      const name = button.getAttribute('data-name');
      const date = button.getAttribute('data-date');
      const email = button.getAttribute('data-email');
      const category = button.getAttribute('data-category');
      const location = button.getAttribute('data-location');
      const description = button.getAttribute('data-description');
      const image = button.getAttribute('data-image');
      const whatsapp = button.getAttribute('data-whatsapp');
      // Tambahkan untuk mengambil tipe dan ID
      const itemType = button.getAttribute('data-type');
      const itemId = button.getAttribute('data-id');

      console.log('Image URL:', image); // Debug URL gambar

      // Isi modal dengan data
      modalItemName.textContent = name;
      modalItemDate.textContent = `Date: ${date}`;
      modalItemEmail.textContent = `Email: ${email}`;
      modalItemCategory.textContent = `Category: ${category}`;
      modalItemLocation.textContent = `Location: ${location}`;
      modalItemDescription.textContent = description;
      modalItemImage.src = image;
      modalWhatsAppButton.href = whatsapp;

      const reporter = button.getAttribute('data-reporter');
      document.getElementById('modalReportedBy').textContent = reporter;

      // Tampilkan tombol berdasarkan tipe laporan
      if (itemType === 'ditemukan') {
        // Jika item ditemukan, tampilkan tombol claim
        modalClaimButton.classList.remove('hidden');
        modalReturnButton.classList.add('hidden');
        modalClaimButton.href = `/claim-item?item_id=${itemId}`;
      } else if (itemType === 'hilang') {
        // Jika item hilang, tampilkan tombol return
        modalReturnButton.classList.remove('hidden');
        modalClaimButton.classList.add('hidden');
        modalReturnButton.href = `/return-item?item_id=${itemId}`;
      } else {
        // Sembunyikan kedua tombol jika tidak jelas jenisnya
        modalClaimButton.classList.add('hidden');
        modalReturnButton.classList.add('hidden');
      }

      // Tampilkan modal
      modal.classList.remove('hidden');
    });
  });

  // Tambahkan event listener untuk tombol close
  document.getElementById('closeModalBtn').addEventListener('click', function() {
    modal.classList.add('hidden');
  });

  // Tambahkan fitur untuk menutup modal ketika mengklik di luar modal
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      modal.classList.add('hidden');
    }
  });

  // Tambahkan keyboard support untuk menutup dengan tombol Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      modal.classList.add('hidden');
    }
  });
</script>

<script>
  // Toggle Dropdown
  document.getElementById('notification-icon').addEventListener('click', function() {
    const dropdown = document.getElementById('notification-dropdown');
    dropdown.classList.toggle('hidden');
  });

  // Tutup dropdown saat klik di luar
  document.addEventListener('click', function(e) {
    const icon = document.getElementById('notification-icon');
    const dropdown = document.getElementById('notification-dropdown');
    if (!icon.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>

<!-- Nontifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function deleteNotification(notificationId) {
    fetch('delete_notification.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notification_id=${notificationId}`,
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Notifikasi berhasil dihapus!',
          }).then(() => {
            location.reload(); // Reload halaman untuk memperbarui daftar notifikasi
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal menghapus notifikasi: ' + (data.error || ''),
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Kesalahan',
          text: 'Terjadi kesalahan saat menghapus notifikasi.',
        });
      });
  }
</script>


<!-- Flowbite -->
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>