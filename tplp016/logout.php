<?php
session_start();

// Hapus semua data session dan akhiri
session_unset();
session_destroy();

header('Location: session.php');
exit;
