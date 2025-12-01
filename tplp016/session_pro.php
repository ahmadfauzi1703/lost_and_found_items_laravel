<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$password = trim($_POST['password'] ?? '');

// Kredensial sederhana; ganti sesuai kebutuhan
$validPassword = '12345';

if ($nama === '' || $password === '') {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama dan password wajib diisi.'];
    header('Location: session.php');
    exit;
}

if ($password !== $validPassword) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Password salah. Gunakan password: ' . $validPassword];
    header('Location: session.php');
    exit;
}

// Simpan data login di session
$_SESSION['user'] = ['name' => $nama];
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Login berhasil.'];

header('Location: session.php');
exit;
