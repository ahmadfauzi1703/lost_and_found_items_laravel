<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign-Up</title>
  <link rel="stylesheet" href="{{ asset('css/sign-up.css') }}" />
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
  <section>
    <div class="sign-in section">
      <div class="logo-img">
        <img
          src="Assets/img/lostnnfoundtransprnt.png"
          height="80px"
          alt="Logo" />
      </div>
      <div class="login-content">
        <div class="login-container">
          <div class="login-welcome">
            <h1>Buat Akun Anda</h1>
            <p>Mari kita mulai dengan membuat akun baru Anda.</p>
          </div>

          <!-- Form Registrasi -->
          <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <!-- First Name -->
            <input class="input-field" type="text" name="first_name" id="first_name" placeholder="Nama Depan Anda" value="{{ old('first_name') }}" required />
            <br />

            <!-- Last Name -->
            <input class="input-field" type="text" name="last_name" id="last_name" placeholder="Nama Belakang Anda" value="{{ old('last_name') }}" required />
            <br />

            <!-- Email -->
            <input class="input-field" type="email" name="email" id="email" placeholder="Email Anda" value="{{ old('email') }}" required />
            <br />

            <!-- Phone -->
            <input class="input-field" type="tel" name="phone" id="phone" placeholder="Nomor Telepon Contoh : +62822xxxx" value="{{ old('phone') }}" required />
            <br />

            <!-- Password -->
            <input class="input-field" type="password" name="password" id="password" placeholder="Password Anda" required />
            <br />

            <!-- Confirm Password -->
            <input class="input-field" type="password" name="password_confirmation" id="confirm_password" placeholder="Konfirmasi Password" required />
            <br />

            <!-- Terms and Conditions -->
            <div class="options">
              <label>
                <input type="checkbox" style="cursor: pointer" required /> Saya menyetujui persyaratan lost and found items
              </label>
            </div>
            <br />

            <!-- Submit Button -->
            <button class="btn-submit" type="submit" style="cursor: pointer">
              Sign Up
            </button>
          </form>


          <div class="sign-up btn">
            <p>
              Sudah memiliki akun?
              <a href="{{ route('login') }}" style="cursor: pointer">Masuk ke sini</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>