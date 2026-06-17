<?php
$baseUrl = "http://localhost/api";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REST API Data Warehouse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 { color: #333; }
        .endpoint {
            background: #f4f4f4;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .method {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 3px;
            margin-right: 10px;
            color: white;
        }
        .get { background: #61affe; }
        .post { background: #49cc90; }
        .put { background: #fca130; }
        .delete { background: #f93e3e; }

        code {
            background: #eee;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<h1>REST API Data Warehouse (Flat Structure)</h1>
<p>
    <strong>Tugas:</strong> Integrasi Data |
    <strong>Mahasiswa:</strong> Muhammad Farid Idris
</p>
<hr>

<div class="endpoint">
    <h3>1. Dimensi Mahasiswa</h3>
    <p><strong>URL:</strong> <code><?= $baseUrl ?>/dim_mahasiswa.php</code></p>
    <p><span class="method get">GET</span> Ambil semua atau cari data (<code>?id_mahasiswa=1</code> atau <code>?search=Budi</code>)</p>
    <p><span class="method post">POST</span> Tambah data (<code>{"nim":"...","nama_mahasiswa":"..."}</code>)</p>
    <p><span class="method put">PUT</span> Update data (<code>{"id_mahasiswa":"...","nim":"...","nama_mahasiswa":"..."}</code>)</p>
    <p><span class="method delete">DELETE</span> Hapus data (<code>{"id_mahasiswa":"..."}</code>)</p>
</div>

<div class="endpoint">
    <h3>2. Dimensi Prodi</h3>
    <p><strong>URL:</strong> <code><?= $baseUrl ?>/dim_prodi.php</code></p>
    <p><span class="method get">GET</span> Ambil semua atau cari data (<code>?id_prodi=1</code> atau <code>?search=Teknik</code>)</p>
    <p><span class="method post">POST</span> Tambah data (<code>{"jurusan":"...","program_studi":"..."}</code>)</p>
    <p><span class="method put">PUT</span> Update data (<code>{"id_prodi":"...","jurusan":"...","program_studi":"..."}</code>)</p>
    <p><span class="method delete">DELETE</span> Hapus data (<code>{"id_prodi":"..."}</code>)</p>
</div>

<div class="endpoint">
    <h3>3. Dimensi Status Mahasiswa</h3>
    <p><strong>URL:</strong> <code><?= $baseUrl ?>/dim_status_mahasiswa.php</code></p>
    <p><span class="method get">GET</span> Ambil semua atau cari data</p>
    <p><span class="method post">POST</span> Tambah data (<code>{"status_mahasiswa":"..."}</code>)</p>
    <p><span class="method put">PUT</span> Update data</p>
    <p><span class="method delete">DELETE</span> Hapus data</p>
</div>

<div class="endpoint">
    <h3>4. Dimensi Status Kerja</h3>
    <p><strong>URL:</strong> <code><?= $baseUrl ?>/dim_status_kerja.php</code></p>
    <p><span class="method get">GET</span> Ambil semua atau cari data</p>
    <p><span class="method post">POST</span> Tambah data (<code>{"status_kerja":"..."}</code>)</p>
    <p><span class="method put">PUT</span> Update data</p>
    <p><span class="method delete">DELETE</span> Hapus data</p>
</div>

</body>
</html>