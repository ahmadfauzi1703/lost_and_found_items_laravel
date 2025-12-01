<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.pwa')
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log-in</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
  <!-- Google Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Playpen+Sans:wght@300;500&display=swap"
    rel="stylesheet" />
  <!-- BX BX ICONS -->
  <link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet" />
</head>

<body>
  {{-- Halaman login pengguna ke sistem Sipanang --}}
  <section>
    <div class="sign-in section">
      <div class="logo-img">
        <img
          src="Assets/img/logo-arka.png" 
          height="80px"
          alt="Logo" />
        <span class="logo-text">Sipanang</span>
      </div>
      <!-- Bagian kanan -->
      <div class="login-content">
        <div class="login-container">
          <div class="login-welcome">
            <h1>Selamat Datang</h1>
            <p>Temukan barang Anda yang hilang di sini.</p>
          </div>

          @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif

          @if(session('message'))
          <div class="alert alert-error">
            {{ session('message') }}
          </div>
          @endif

          <form action="{{ route('login.submit') }}" method="post">
            @csrf <!-- Token CSRF -->

            <input
              value="{{ old('email') }}"
              class="input-field"
              name="email"
              id="email"
              type="text"
              placeholder="Masukan Email Anda"
              required />
            <br />

            <div class="pwd-btn">
              <input
                class="input-field"
                name="password"
                id="password"
                type="password"
                placeholder="Masukan Password Anda"
                required />
            </div>

            <div class="options">
              <label><input type="checkbox" name="remember_me" style="cursor: pointer" />remember me</label>
              <a href="{{ route('password.request') }}">Lupa Password?</a>
            </div>

            <button class="btn-submit" type="submit" style="cursor: pointer">
              Sign in
            </button>
          </form>

          <div class="sign-up btn">
            <p>
              Tidak memiliki akun? Silakan hubungi administrator untuk membuat akun.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const passwordInput = document.getElementById('password');
    const minimumLength = 6; // Sesuaikan dengan validasi di controller (min:6)

    // Cek apakah elemen password-error sudah ada, jika belum buat baru
    let passwordError = document.getElementById('password-error');
    if (!passwordError) {
      passwordError = document.createElement('div');
      passwordError.id = 'password-error';
      passwordError.className = 'alert alert-error';
      passwordError.style.display = 'none';
      passwordError.textContent = 'Password minimal harus ' + minimumLength + ' karakter.';

      // Masukkan elemen error setelah div pwd-btn
      const pwdBtn = document.querySelector('.pwd-btn');
      if (pwdBtn) {
        pwdBtn.parentNode.insertBefore(passwordError, pwdBtn.nextSibling);
      }
    }

    // Hapus event listener untuk input
    // Hanya validasi saat form submit
    form.addEventListener('submit', function(event) {
      if (passwordInput.value.length < minimumLength) {
        event.preventDefault();
        passwordError.style.display = 'block';
        passwordInput.focus();
      } else {
        passwordError.style.display = 'none';
      }
    });
  });
</script>

</html>
