<?php
session_start();

// Ambil dan hapus pesan flash jika ada
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Jika sudah login, tampilkan sapaan dan tombol logout
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .card { border: 1px solid #ddd; padding: 1.5rem; max-width: 420px; border-radius: 8px; }
        .row { margin-bottom: 0.75rem; }
        .error { color: #b91c1c; margin-bottom: 1rem; }
        .success { color: #15803d; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <h2>Demo Login/Logout dengan Session PHP</h2>

    <?php if ($flash): ?>
        <div class="<?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
    <?php endif; ?>

    <?php if ($isLoggedIn): ?>
        <div class="card">
            <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['user']['name']); ?></strong>!</p>
            <p>Anda sudah login menggunakan session. Klik tombol di bawah untuk logout.</p>
            <form method="POST" action="logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
    <?php else: ?>
        <div class="card">
            <form method="POST" action="session_pro.php">
                <div class="row">
                    <label for="nama">Nama</label><br>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="row">
                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
