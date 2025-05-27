<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Page | Lost and Found Items</title>
  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/index2.css') }}" />
  <!-- Boxicons CSS -->
  <link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Playpen+Sans:wght@300;500&display=swap"
    rel="stylesheet" />
  <!-- Flaticon -->
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-straight/css/uicons-solid-straight.css'>
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
  <!-- Header Section -->
  <header>
    <nav>
      <div class="logo">
        <img src="Assets/img/lostnfoundlogo.png" height="70px" />
      </div>
      <ul id="menuList">
        <li><a class="login-btn" href="{{ route('login') }}">Log in</a></li>
        <li><a class="sign-up btn" href="{{ route('register') }}">Sign Up</a></li>
      </ul>
      <div class="menu-icon">
        <i class="bx bx-menu" onclick="toggleMenu()"></i>
      </div>
    </nav>
  </header>
  <!-- Header Section -->

  <!-- Search Section -->
  <section>
    <div class="search-content">
      <p class="qoute-search"><i class='bx bxs-quote-left'></i> Jangan khawatir, bersama kami barang Anda yang hilang akan kembali. Gunakan layanan kami untuk memulai pencarian sekarang.</p>
      <form method="GET" action="{{ route('landing.items') }}">
        <div class="search-bar">
          <!-- Dropdown untuk kategori -->
          <select id="category" name="category" class="btn-list">
            <option value="">Semua Kategori</option>
            <option value="Perhiasan Khusus" {{ request('category') == 'Perhiasan Khusus' ? 'selected' : '' }}>Perhiasan Khusus</option>
            <option value="Elektronik" {{ request('category') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
            <option value="Buku & Dokumen" {{ request('category') == 'Buku & Dokumen' ? 'selected' : '' }}>Buku & Dokumen</option>
            <option value="Tas & Dompet" {{ request('category') == 'Tas & Dompet' ? 'selected' : '' }}>Tas & Dompet</option>
            <option value="Perlengkapan Pribadi" {{ request('category') == 'Perlengkapan Pribadi' ? 'selected' : '' }}>Perlengkapan Pribadi</option>
            <option value="Peralatan Praktikum" {{ request('category') == 'Peralatan Praktikum' ? 'selected' : '' }}>Peralatan Praktikum</option>
            <option value="Aksesori" {{ request('category') == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
            <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>

          <!-- Input untuk pencarian -->
          <input
            type="text"
            id="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search" />

          <!-- Tombol Submit -->
          <button type="submit" class="btn btn-search"><i style="font-size: 1.1rem" class='bx bx-search'></i></button>
        </div>
        <p style="text-align: center; color: #fff;">Contoh: <span style="color: white;">KTM, Dompet, Kunci Motor, Jam Tangan</span></p>
      </form>
    </div>
  </section>

  <!-- Report List Section -->
  <!-- Report List Section -->
  <section>
    <div class="test">
      <h2>Laporan <span style="color: #124076;">Barang</span> Terkini</h2>
      <p style="text-align: center;">Lihatlah barang-barang yang baru-baru ini dibagikan oleh para Mahasiswa.</p>
      <div class="card-container" id="cardContainer">
        @if ($items->count() > 0)
        <div class="card-content">
          @foreach ($items as $item)
          <div class="card">
            @php
            $type = strtolower(trim($item->type));
            $statusText = ($type == 'hilang') ? 'Lost' : 'Found';
            $statusClass = ($type == 'hilang') ? 'lost' : 'found';
            $imagePath = !empty($item->photo_path) ? $item->photo_path : 'images/default-image.jpg';
            @endphp

            <span class="status {{ $statusClass }}">
              {{ $statusText }}
            </span>
            <div class="card-image">
              @php
              $imagePath = !empty($item->photo_path) ? 'storage/' . $item->photo_path : 'images/default-image.jpg';
              @endphp
              <img src="{{ asset($imagePath) }}" alt="Report Image" />
            </div>
            <div class="card-details">
              <h3>{{ $item->item_name }}</h3>
              <p>
                <i class="bx bx-calendar-alt calendar-icon"></i>
                {{ $item->date_of_event }}
              </p>
              <button class="btn btn-details" onclick="showLoginModal()">Detail</button>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <p>No reports found. Try different filters or keywords.</p>
        @endif
      </div>

      <!-- Tombol Show More dan Collapse -->
      <div style="text-align: center; margin-top: 20px;">
        <!-- Tombol Show More -->
        <button id="showMoreBtn" class="btn btn-show-more" onclick="showMore()">
          <i class='bx bx-chevrons-down'></i> Tampilkan Lainnya
        </button>

        <!-- Tombol Collapse -->
        <button id="collapseBtn" class="btn btn-collapse" onclick="collapseCards()" style="display: none;">
          <i class='bx bx-chevrons-up'></i> Tutup
        </button>
      </div>
    </div>
  </section>

  <!-- Report Section -->
  <section>
    <div class="report-main">
      <div class="report">
        <div class="report-content">
          <h2>Laporkan Barang Anda!</h2>
        </div>
        <div class="report-section">
          <div class="report-btn">
            <a href="{{ route('register') }}"><button type="submit">Saya Telah Kehilangan</button></a>
          </div>
          <div class="report-btn">
            <a href="{{ route('register') }}"><button type="submit">Saya Telah Menemukan</button></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Report Section -->

  <!-- Footer Section -->
  <section>
    <div class="footer">
      <div class="footer-main">
        <div class="fotter-img">
          <a href="#"><img src="Assets/img/lostnfoundlogowhite.png" height="85px" /></a>
        </div>
      </div>
      <div class="footer-section">
        <div class="footer-content">
          <h3>About</h3>
          <ul>
            <a href="about-us-non-log.html">
              <li>About Lost and Found Items</li>
            </a>
            <a href="terms-condition.html">
              <li>Terms and Condition Lost And Found Items</li>
            </a>
            <a href="terms-condition.html">
              <li>FaQ</li>
            </a>
          </ul>
        </div>
        <div class="footer-content">
          <h3>Lost and Found Items</h3>
          <ul>
            <a href="non-log-dasboard.php">
              <li>Lost Items</li>
            </a>
            <a href="non-log-dasboard.php">
              <li>Found Items</li>
            </a>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <!-- Footer Section -->

  <script>
    let menuList = document.getElementById("menuList");
    menuList.style.maxHeight = "0px";

    function toggleMenu() {
      if (menuList.style.maxHeight == "0px") {
        menuList.style.maxHeight = "300px";
      } else {
        menuList.style.maxHeight = "0px";
      }
    }
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll(".card");
      const cardContainer = document.getElementById("cardContainer");
      const showMoreBtn = document.getElementById("showMoreBtn");
      const collapseBtn = document.getElementById("collapseBtn");

      function updateVisibleCards() {
        const isResponsive = window.innerWidth <= 768;
        const maxVisible = isResponsive ? 2 : 8; // Maksimal card: 2 untuk responsif, 8 untuk desktop

        // Sembunyikan card yang melebihi batas
        cards.forEach((card, index) => {
          card.style.display = index < maxVisible ? "block" : "none";
        });

        // Atur tinggi container hanya pada mode responsif
        if (isResponsive) {
          const cardHeight = 220; // Tinggi rata-rata card pada responsif
          const padding = 20; // Tambahkan ruang ekstra untuk memastikan tidak ada yang terpotong
          const visibleCards = Math.min(cards.length, maxVisible);
          cardContainer.style.height = `${visibleCards * cardHeight + padding}px`; // Atur tinggi container dengan padding tambahan
        } else {
          cardContainer.style.height = "auto"; // Kembali ke auto di desktop
        }

        // Reset tombol
        collapseBtn.style.display = "none"; // Sembunyikan tombol "Collapse"
        showMoreBtn.style.display = cards.length > maxVisible ? "inline-block" : "none"; // Tampilkan tombol "Show More" hanya jika ada lebih banyak card
      }

      // Fungsi untuk menampilkan semua card
      window.showMore = function() {
        cards.forEach((card) => {
          card.style.display = "block"; // Tampilkan semua card
        });

        // Tambahkan tinggi container hanya di mode responsif
        if (window.innerWidth <= 768) {
          const cardHeight = 220; // Tinggi rata-rata card pada responsif
          const padding = 20; // Tambahkan padding ekstra
          cardContainer.style.height = `${cards.length * cardHeight + padding}px`;
        }

        showMoreBtn.style.display = "none"; // Sembunyikan tombol "Show More"
        collapseBtn.style.display = "inline-block"; // Tampilkan tombol "Collapse"
      };

      // Fungsi untuk menyembunyikan kembali card yang melebihi batas
      window.collapseCards = function() {
        updateVisibleCards(); // Reset ke jumlah card sesuai ukuran layar
      };

      // Perbarui card yang terlihat saat halaman dimuat
      updateVisibleCards();

      // Tambahkan event listener untuk resize
      window.addEventListener("resize", updateVisibleCards);
    });
  </script>

  <script>
    function showLoginModal() {
      const modal = document.getElementById('loginModal');
      modal.classList.add('show'); // Tambahkan kelas 'show'
    }

    function closeModal() {
      const modal = document.getElementById('loginModal');
      modal.classList.remove('show'); // Hapus kelas 'show'
    }
  </script>


  <script
    src="https://kit.fontawesome.com/f8e1a90484.js"
    crossorigin="anonymous"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.all.min.js"></script>

  <!-- Alert -->
  <script>
    function showLoginModal() {
      Swal.fire({
        title: 'Silahkan Login untuk Mengakses Detail',
        text: 'Anda harus login untuk melihat detail laporan ini. Silakan masuk atau daftar untuk melanjutkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Login',
        cancelButtonText: 'Batalkan',
        reverseButtons: true,
        customClass: {
          confirmButton: 'btn-login-custom', // Kelas untuk tombol Login
          cancelButton: 'btn-cancel-custom' // Kelas untuk tombol Cancel
        }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '{{ route("login") }}'; // Redirect ke halaman login
        }
      });
    }
  </script>

</body>

</html>