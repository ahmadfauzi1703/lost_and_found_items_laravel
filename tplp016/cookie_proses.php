<?php
session_start();

$fullname = trim($_POST['fullname'] ?? '');
$password = trim($_POST['password'] ?? '');

// Password demo; ganti sesuai kebutuhan
$validPassword = '12345';

if ($fullname === '' || $password === '') {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Fullname dan password wajib diisi.'];
    header('Location: cookie.php');
    exit;
}

if ($password !== $validPassword) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Password salah. Gunakan password: ' . $validPassword];
    header('Location: cookie.php');
    exit;
}

// Simpan ke session
$_SESSION['login_name'] = $fullname;
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Login berhasil.'];

header('Location: cookie.php');
exit;
