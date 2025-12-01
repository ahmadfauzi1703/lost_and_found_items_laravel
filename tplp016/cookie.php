<?php
session_start();

// Ambil flash message sederhana
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Cek apakah sudah login
$isLoggedIn = isset($_SESSION['login_name']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Session (tanpa Cookie)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .card { border: 1px solid #ddd; padding: 1.5rem; max-width: 420px; border-radius: 8px; }
        .row { margin-bottom: 0.75rem; }
        .flash { margin-bottom: 1rem; padding: 0.75rem; border-radius: 6px; }
        .error { background: #fee2e2; color: #b91c1c; }
        .success { background: #dcfce7; color: #15803d; }
    </style>
</head>
<body>
    <h2>Demo Login/Logout dengan Session PHP</h2>

    <?php if ($flash): ?>
        <div class="flash <?php echo $flash['type']; ?>"><?php echo htmlspecialchars($flash['message']); ?></div>
    <?php endif; ?>

    <?php if ($isLoggedIn): ?>
        <div class="card">
            <p>Welcome <strong><?php echo htmlspecialchars($_SESSION['login_name']); ?></strong></p>
            <p>Anda login menggunakan session (tanpa cookie).</p>
            <form action="cookie_logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        </div>
    <?php else: ?>
        <div class="card">
            <form action="cookie_proses.php" method="post">
                <div class="row">
                    <label for="fullname">Fullname</label><br>
                    <input type="text" id="fullname" name="fullname" required>
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
