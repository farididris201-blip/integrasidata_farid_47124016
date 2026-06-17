<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "dw_mahasiswa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "status" => [
            "code"        => 500,
            "description" => "Gagal koneksi ke database: " . $conn->connect_error
        ]
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
?>
