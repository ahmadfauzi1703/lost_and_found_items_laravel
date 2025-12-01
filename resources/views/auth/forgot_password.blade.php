<!DOCTYPE html>
<html lang="id">

<head>
  @include('partials.pwa')
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Playpen+Sans:wght@300;500&display=swap"
    rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
  <section>
    <div class="sign-in section">
      <div class="logo-img">
        <img src="/Assets/img/logo-arka.png" height="80" alt="Logo" />
        <span class="logo-text">Sipanang</span>
      </div>
      <div class="login-content">
        <div class="login-container">
          <div class="login-welcome">
            <h1>Lupa Password</h1>
            <p>Masukkan email terdaftar, kami akan kirimkan tautan untuk mengatur ulang password.</p>
          </div>

          @if(session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif

          @if($errors->any())
            <div class="alert alert-error">
              {{ $errors->first('email') ?? 'Terjadi kesalahan. Coba lagi.' }}
            </div>
          @endif

          <form action="{{ route('password.email') }}" method="post">
            @csrf
            <input
              value="{{ old('email') }}"
              class="input-field"
              name="email"
              id="email"
              type="email"
              placeholder="Masukkan email Anda"
              required />

            <button class="btn-submit" type="submit" style="cursor: pointer">
              Kirim tautan reset
            </button>
          </form>

          <div class="sign-up btn">
            <a href="{{ route('login') }}">Kembali ke halaman login</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>
