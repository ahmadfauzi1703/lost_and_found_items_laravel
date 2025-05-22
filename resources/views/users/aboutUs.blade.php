<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us – Lost & Found Items</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:wght@100;300;400;700;900&family=Playpen+Sans:wght@300;500&display=swap" rel="stylesheet" />
  <!-- Ionicons -->
  <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <!-- Flowbite -->
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="font-[Lato] min-h-screen flex flex-col">
  <!-- =================== HEADER =================== -->
  <header class="bg-white w-full shadow-sm">
    <nav class="flex justify-between items-center w-[90%] xl:w-[70%] mx-auto">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2">
        <img class="my-3 h-16 sm:h-20 cursor-pointer" src="Assets/img/lostnfoundlogo.png" alt="Lost &amp; Found Logo" />
      </a>

      <!-- Right‑side controls -->
      <div class="flex items-center gap-4 sm:gap-6 relative">
        <!-- Filter Activity Button -->
        <div class="relative">
          <button id="filterActivityButton" type="button" class="flex items-center text-[#124076] px-3 py-2 rounded-lg hover:bg-gray-100">
            <i class="bx bx-filter text-2xl mr-1"></i>
            <span class="hidden sm:inline">Filter Activity</span>
          </button>
          <div id="filterActivityDropdown" class="hidden absolute top-full mt-2 right-0 w-48 bg-white rounded-lg shadow-md z-30">
            <ul class="py-1 text-sm text-gray-700">
              <li><a href="{{ route('activity') }}" class="block px-4 py-2 hover:bg-gray-100">All</a></li>
              <li><a href="{{ route('activity', ['type' => 'lost']) }}" class="block px-4 py-2 hover:bg-gray-100">Lost Items</a></li>
              <li><a href="{{ route('activity', ['type' => 'found']) }}" class="block px-4 py-2 hover:bg-gray-100">Found Items</a></li>
              <li><a href="{{ route('activity', ['type' => 'returned']) }}" class="block px-4 py-2 hover:bg-gray-100">Returned</a></li>
            </ul>
          </div>
        </div>

        <!-- Notification -->
        <button id="notification-icon" type="button" class="relative text-[#124076]">
          <i class="bx bxs-bell text-3xl"></i>
          <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-2">0</span>
        </button>
        <div id="notification-dropdown" class="absolute top-full mt-2 right-0 w-80 bg-white shadow-lg rounded-lg hidden z-30">
          <ul class="divide-y divide-gray-200">
            <li class="p-4 text-center text-gray-500">Tidak ada pemberitahuan baru</li>
          </ul>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative">
          <button id="menuDropdownButton" type="button" class="flex items-center text-sm font-medium text-gray-900 rounded-full hover:text-blue-600">
            <img class="w-8 h-8 rounded-full mr-2" src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : asset('Assets/img/default-avatar.png') }}" alt="User Avatar" />
            <span class="whitespace-nowrap">{{ $user->first_name }} {{ $user->last_name }}</span>
            <i class="bx bx-chevron-down text-gray-500 ml-2 text-lg"></i>
          </button>
          <div id="menuDropdown" class="hidden absolute top-full mt-2 right-0 bg-white divide-y divide-gray-100 rounded-lg shadow-md w-44 z-30">
            <ul class="py-1 text-sm text-gray-700">
              <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Home</a></li>
              <li><a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a></li>
              <li><a href="{{ route('activity') }}" class="block px-4 py-2 hover:bg-gray-100">Activity</a></li>
              <li><a href="{{ route('about-us') }}" class="block px-4 py-2 hover:bg-gray-100">About Us</a></li>
            </ul>
            <div class="py-1">
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">Sign out</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- =================== MAIN =================== -->
  <main class="flex-grow bg-[#91B0D3] py-10 px-4 sm:px-8 lg:px-20">
    <!-- About Us Banner -->
    <section class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
      <div class="relative">
        <img src="Assets/img/gambaritems.jpg" alt="Background" class="w-full h-64 object-cover" />
        <div class="absolute inset-0 bg-gray-800/40 flex items-center justify-center">
          <h1 class="text-white text-4xl font-bold">Tentang Kami</h1>
        </div>
      </div>
      <div class="p-8">
        <p class="text-gray-600 leading-relaxed">
          Selamat datang di <strong>Lost &amp; Found Items</strong>, mitra terpercaya Anda dalam menyatukan kembali mahasiswa dengan barang mereka yang hilang. Didirikan pada tahun 2024, misi kami adalah menyediakan platform yang cepat, mudah, dan efisien bagi komunitas di sekitar Telkom University untuk menemukan kembali barang mereka.
        </p>
      </div>
    </section>

    <!-- ================= FEEDBACK FORM ================= -->
    <form id="feedback-form" method="POST" action="{{ route('feedback.store') }}" class="max-w-4xl mx-auto mt-8 space-y-8">
      @csrf
      <input type="hidden" name="rating" id="rating-input" value="0" />

      <!-- Star Rating Card -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-2">Berikan Umpan Balik</h2>
        <p class="text-gray-600 mb-4">Bagaimana Anda menggambarkan pengalaman Anda setelah menggunakan layanan kami?</p>
        <div class="flex space-x-2">
          <span class="star text-gray-300 text-2xl cursor-pointer" data-value="1">&#9733;</span>
          <span class="star text-gray-300 text-2xl cursor-pointer" data-value="2">&#9733;</span>
          <span class="star text-gray-300 text-2xl cursor-pointer" data-value="3">&#9733;</span>
          <span class="star text-gray-300 text-2xl cursor-pointer" data-value="4">&#9733;</span>
          <span class="star text-gray-300 text-2xl cursor-pointer" data-value="5">&#9733;</span>
        </div>
      </div>

      <!-- Description & Comments Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Feedback Description -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-2xl font-semibold mb-2">Deskripsi Umpan Balik</h2>
          <textarea id="feedback-description" name="description" rows="6" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis umpan balik Anda" required></textarea>
        </div>

        <!-- Comments & Suggestions -->
        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col">
          <div class="flex-1">
            <h2 class="text-2xl font-semibold mb-2">Komentar dan Saran</h2>
            <textarea id="comments-suggestions" name="comments" rows="6" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tulis komentar dan saran Anda" required></textarea>
          </div>
          <button type="submit" class="mt-4 self-end text-white bg-[#124076] hover:bg-[#0f355f] focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-3">Submit</button>
        </div>
      </div>
    </form>
  </main>

  <!-- =================== FOOTER =================== -->
  <footer class="bg-[#124076] w-full">
    <div class="mx-auto max-w-screen-xl p-6 lg:py-8">
      <div class="md:flex md:justify-between">
        <a href="#" class="mb-6 md:mb-0 flex items-center">
          <img src="Assets/img/lostnfoundlogowhite.png" class="h-28" alt="Lost &amp; Found Logo" />
        </a>
        <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3 text-white">
          <div>
            <h2 class="mb-6 text-sm font-semibold uppercase">About</h2>
            <ul class="font-medium">
              <li class="mb-4"><a href="about-us-non-log.html" class="hover:underline">About Lost and Found Items</a></li>
            </ul>
          </div>
          <div>
            <h2 class="mb-6 text-sm font-semibold uppercase">Lost and Found Items</h2>
            <ul class="font-medium">
              <li class="mb-4"><a href="#" class="hover:underline">Lost Items</a></li>
              <li><a href="#" class="hover:underline">Found Items</a></li>
            </ul>
          </div>
          <div>
            <h2 class="mb-6 text-sm font-semibold uppercase">Legal</h2>
            <ul class="font-medium">
              <li class="mb-4"><a href="about-us.php" class="hover:underline">Feedback</a></li>
              <li><a href="terms-condition.html" class="hover:underline">Terms &amp; Conditions</a></li>
            </ul>
          </div>
        </div>
      </div>
      <hr class="my-6 border-white" />
      <p class="text-center text-sm text-white">© 2024 <a href="/" class="hover:underline">Lost and Found Team</a>. All Rights Reserved.</p>
    </div>
  </footer>

  <!-- =================== SCRIPTS =================== -->
  <script>
    /* ================= Nav & Dropdowns ================= */
    document.getElementById('notification-icon').addEventListener('click', function (event) {
      event.stopPropagation();
      document.getElementById('notification-dropdown').classList.toggle('hidden');
    });

    document.getElementById('menuDropdownButton').addEventListener('click', function (event) {
      event.stopPropagation();
      document.getElementById('menuDropdown').classList.toggle('hidden');
    });

    document.getElementById('filterActivityButton').addEventListener('click', function (event) {
      event.stopPropagation();
      document.getElementById('filterActivityDropdown').classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
      const outside = (selector, evt) => !document.querySelector(selector).contains(evt.target) && !evt.target.closest(selector);
      if (outside('#notification-dropdown', event) && !event.target.closest('#notification-icon')) {
        document.getElementById('notification-dropdown').classList.add('hidden');
      }
      if (outside('#menuDropdown', event) && !event.target.closest('#menuDropdownButton')) {
        document.getElementById('menuDropdown').classList.add('hidden');
      }
      if (outside('#filterActivityDropdown', event) && !event.target.closest('#filterActivityButton')) {
        document.getElementById('filterActivityDropdown').classList.add('hidden');
      }
    });

    /* ================= Star Rating & Form Validation ================= */
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    let selectedRating = 0;

    stars.forEach((star, index) => {
      star.addEventListener('click', () => {
        selectedRating = index + 1;
        ratingInput.value = selectedRating;
        updateStars(selectedRating);
      });

      star.addEventListener('mouseover', () => updateStars(index + 1));
      star.addEventListener('mouseout', () => updateStars(selectedRating));
    });

    function updateStars(rating) {
      stars.forEach((star, idx) => {
        star.classList.toggle('text-yellow-400', idx < rating);
        star.classList.toggle('text-gray-300', idx >= rating);
      });
    }

    document.getElementById('feedback-form').addEventListener('submit', function (event) {
      const desc = document.getElementById('feedback-description').value.trim();
      const comments = document.getElementById('comments-suggestions').value.trim();
      const ratingVal = parseInt(ratingInput.value);

      if (!desc || !comments) {
        alert('Harap lengkapi semua kolom sebelum mengirim feedback.');
        event.preventDefault();
        return;
      }
      if (!ratingVal || ratingVal === 0) {
        alert('Harap berikan rating bintang sebelum mengirim.');
        event.preventDefault();
      }
    });
  </script>
</body>

</html>
