<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>my-profile</title>
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
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
  <!-- Header Section -->

  <body class="font-[Lato] h-screen">
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


    <!-- Header Section -->

    <!-- Profile Section -->

    <div class="bg-[#91B0D3] min-h-screen py-10">
      <!-- Profile Section -->
      <section class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-8 mb-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <img
              src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('Assets/img/default-avatar.png') }}"
              alt="Profile Picture"
              class="w-36 h-36 rounded-full border-2 border-[#124076] shadow-lg object-cover" />
            <span data-modal-target="default-modal-profile" data-modal-toggle="default-modal-profile" class="absolute mt-[6rem] text-white p-[1] border-2 border-[#124076] bg-[#124076] rounded-md cursor-pointer">✎</span>

            <!-- Modal untuk Edit Profile -->
            <div id="default-modal-profile" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-[calc(100%-1rem)] max-h-full md:inset-0 overflow-y-auto overflow-x-hidden">
              <div class="relative p-4 w-full max-w-2xl max-h-full mx-0">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                  <!-- Modal header -->
                  <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-black">Edit Profile</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal-profile">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                      </svg>
                      <span class="sr-only">Close modal</span>
                    </button>
                  </div>
                  <!-- Modal body -->
                  <div class="p-4 md:p-5 space-y-4">
                    <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="mb-4 flex justify-center items-center">
                        <img
                          src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('Assets/img/default-avatar.png') }}"
                          alt="Profile Picture"
                          class="w-52 h-52 object-cover rounded-full" />
                      </div>

                      <div class="flex items-center space-x-4">
                        <div class="w-full">
                          <label class="block mb-2 text-sm font-medium text-gray-900" for="profile_picture">Upload file</label>
                          <input
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                            id="profile_picture"
                            name="profile_picture"
                            type="file"
                            accept="image/*" />
                        </div>
                        <button
                          type="submit"
                          class="text-white bg-[#124076] hover:bg-[#255386] font-medium rounded-lg text-sm px-5 py-2.5 mt-7">
                          Upload
                        </button>
                      </div>
                    </form>

                    <!-- Tombol Hapus Gambar Profil -->
                    <form action="{{ route('profile.picture.delete') }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200">
                        Hapus Gambar Profil
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- End of Modal -->

            <div>
              <h2 class="text-2xl font-semibold">{{ $user->first_name }} {{ $user->last_name }}</h2>
              <p class="text-gray-500">{{ $user->nim ?? 'Belum ditambahkan' }}</p>
              <p class="text-gray-500">Mahasiswa</p>
            </div>
          </div>
          <div class="flex space-x-2">
            <!-- Tombol Sign out dengan SweetAlert -->
            <button
              id="logoutButton"
              class="bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200 flex items-center">
              Sign out
            </button>
          </div>
        </div>
      </section>

      <!-- Personal Information Section -->
      <section class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-8 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Informasi Pribadi</h3>
          <!-- Modal toggle -->
          <button data-modal-target="default-modal-1" data-modal-toggle="default-modal-1" class="bg-gray-200 text-gray-600 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
            Edit <span class="ml-2">✎</span>
          </button>

          <!-- Main modal Personal Information -->
          <div id="default-modal-1" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  <h3 class="text-xl font-semibold text-black">
                    Informasi Pribadi
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal-1">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <!-- Modal content -->
                <div class="p-4 md:p-5 space-y-4">
                  <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                      <label for="first_name" class="block text-gray-600">Nama Depan</label>
                      <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="mb-4">
                      <label for="last_name" class="block text-gray-600">Nama Belakang</label>
                      <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="mb-4">
                      <label for="nim" class="block text-gray-600">NIM</label>
                      <input placeholder="-- Tambahkan Nim --" type="text" id="nim" name="nim" value="{{ $user->nim ?? '' }}" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="mb-4">
                      <label for="email" class="block text-gray-600">Email</label>
                      <input type="email" id="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="mb-4">
                      <label for="phone" class="block text-gray-600">Nomor Telepon</label>
                      <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                      <!-- Tombol Update dengan SweetAlert -->
                      <button
                        id="updateButton"
                        type="submit"
                        class="text-white bg-[#124076] hover:bg-[#255386] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Update
                      </button>
                    </div>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-gray-600 font-medium">Nama Depan</p>
            <p class="text-gray-800">{{ $user->first_name }}</p>
          </div>
          <div>
            <p class="text-gray-600 font-medium">Nama Belakang</p>
            <p class="text-gray-800">{{ $user->last_name }}</p>
          </div>
          <div>
            <p class="text-gray-600 font-medium">NIM</p>
            <p class="text-gray-800">{{ $user->nim ?? 'Belum ditambahkan' }}</p>
          </div>
          <div>
            <p class="text-gray-600 font-medium">Alamat Email</p>
            <p class="text-gray-800">{{ $user->email }}</p>
          </div>
          <div>
            <p class="text-gray-600 font-medium">Nomor Telepon</p>
            <p class="text-gray-800">{{ $user->phone ?? 'Belum ditambahkan' }}</p>
          </div>
        </div>
      </section>

      <!-- Address Information Section -->
      <section class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-8">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-semibold">Informasi Alamat</h3>
          <button data-modal-target="default-modal-2" data-modal-toggle="default-modal-2" class="bg-gray-200 text-gray-600 px-4 py-2 rounded hover:bg-gray-300 flex items-center">
            Edit <span class="ml-2">✎</span>
          </button>

          <!-- Main modal Personal Information -->
          <div id="default-modal-2" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
              <!-- Modal content -->
              <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                  <h3 class="text-xl font-semibold text-black">
                    Informasi Alamat
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal-2">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                  <form action="{{ route('profile.update') }}" method="POST">
                    @csrf

                    <!-- Field wajib yang perlu disertakan -->
                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <label class="block font-bold">Test Update Alamat:</label>
                    <input type="text" name="address" value="{{ $user->address ?? '' }}"
                      class="w-full border p-2 rounded" placeholder="Masukkan alamat disini">

                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b mt-4">
                      <button
                        id="updateAddressButton"
                        type="submit"
                        class="text-white bg-[#124076] hover:bg-[#255386] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Update
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div>
          <p class="text-gray-600 font-medium">Address</p>
          <p class="text-gray-800">
            {{ $user->address ?? 'Belum ditambahkan' }}
          </p>
        </div>
      </section>
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
                class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
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
                class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">
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
          <span class="text-sm text-white text-center">© 2024 <a href="/" class="hover:underline">Lost and Found Team</a>.
            All Rights Reserved.
          </span>
        </div>
      </div>
    </footer>

    <!-- Javascript -->
    <script>
      const navLinks = document.querySelector(".nav-links");

      function onToggleMenu(e) {
        e.name = e.name === "menu" ? "close" : "menu";
        navLinks.classList.toggle("top-[11%]");
      }
    </script>

    <script>
      const notificationIcon = document.getElementById('notification-icon');
      const notificationDropdown = document.getElementById('notification-dropdown');

      notificationIcon.addEventListener('click', () => {
        notificationDropdown.classList.toggle('hidden');
      });

      document.addEventListener('click', (e) => {
        if (!notificationIcon.contains(e.target) && !notificationDropdown.contains(e.target)) {
          notificationDropdown.classList.add('hidden');
        }
      });
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.js"></script>

    <!-- button logout -->
    <script>
      document.getElementById('logoutButton').addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: 'Anda akan keluar dari akun ini!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, keluar!',
          cancelButtonText: 'Batalkan',
          confirmButtonColor: "#124076",
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            // Buat form untuk logout secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';

            // Tambahkan CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
          }
        });
      });
    </script>

    <!-- button update -->
    <script>
      document.getElementById('updateButton').addEventListener('click', function(event) {
        event.preventDefault();


        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: 'Perubahan yang Anda buat akan disimpan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, simpan perubahan!',
          cancelButtonText: 'Batalkan',
          confirmButtonColor: "#124076",
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {

            this.closest('form').submit();
          }
        });
      });
    </script>

    <script>
      // Ambil parameter dari URL
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const status = urlParams.get('status');
      const message = urlParams.get('message');

      // Tampilkan SweetAlert jika ada status dan message
      if (status && message) {
        if (status === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Perubahan Berhasil!',
            text: 'Perubahan Anda telah berhasil diperbarui.',
            confirmButtonText: 'Oke!',
            confirmButtonColor: '#124076'
          });
        } else if (status === 'error') {
          Swal.fire({
            icon: 'error',
            title: 'Perubahan Gagal!',
            text: 'Ada masalah saat mengatur perubahan Anda. Silakan coba lagi.',
            confirmButtonText: 'OK'
          });
        }

        // Hapus parameter dari URL setelah SweetAlert muncul
        const newUrl = window.location.origin + window.location.pathname;
        history.replaceState(null, '', newUrl);
      }
    </script>

    <script>
      (function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const status = urlParams.get('status');
        const message = urlParams.get('message');

        if (status && message) {
          if (status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: message,
            });
          } else if (status === 'error') {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: message,
            });
          }

          // Hapus parameter dari URL setelah alert ditampilkan
          const newUrl = window.location.origin + window.location.pathname;
          history.replaceState(null, '', newUrl);
        }
      })();
    </script>


    <script>
      if (!window.alertDisplayed) { // Pastikan SweetAlert hanya dijalankan sekali
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const status = urlParams.get('status');
        const message = urlParams.get('message');

        // Tampilkan SweetAlert jika ada status dan message
        if (status && message) {
          if (status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: message,
            });
          } else if (status === 'error') {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: message,
            });
          }
        }

        // Tandai bahwa alert sudah ditampilkan
        window.alertDisplayed = true;
      }
    </script>


    <script>
      document.getElementById('updateAddressButton').addEventListener('click', function(event) {
        event.preventDefault();
        const form = this.closest('form');

        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: 'Perubahan alamat akan disimpan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, simpan perubahan!',
          cancelButtonText: 'Batalkan',
          confirmButtonColor: "#124076",
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    </script>


    <!-- Flowbite CDN JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
  </body>

</html>