<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'database.php';
$method = $_SERVER['REQUEST_METHOD'];
$result = [];

if ($method == 'GET') {
    if (isset($_GET['id_mahasiswa'])) {
        $id = $conn->real_escape_string($_GET['id_mahasiswa']);
        $query = $conn->query("SELECT * FROM dim_mahasiswa WHERE id_mahasiswa = '$id'");
        if ($query && $query->num_rows > 0) {
            $result['status'] = ["code" => 200, "description" => "Data mahasiswa ditemukan"];
            $result['result'] = $query->fetch_assoc();
        } else {
            $result['status'] = ["code" => 404, "description" => "Data mahasiswa tidak ditemukan"];
        }
    } elseif (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $query = $conn->query("SELECT * FROM dim_mahasiswa WHERE nim LIKE '%$search%' OR nama_mahasiswa LIKE '%$search%'");
        if ($query && $query->num_rows > 0) {
            $data = []; while ($row = $query->fetch_assoc()) $data[] = $row;
            $result['status'] = ["code" => 200, "description" => "Data mahasiswa ditemukan"];
            $result['total'] = count($data); $result['result'] = $data;
        } else {
            $result['status'] = ["code" => 404, "description" => "Data mahasiswa tidak ditemukan"];
        }
    } else {
        $query = $conn->query("SELECT * FROM dim_mahasiswa ORDER BY id_mahasiswa ASC");
        if ($query) {
            $data = []; while ($row = $query->fetch_assoc()) $data[] = $row;
            $result['status'] = ["code" => 200, "description" => "Menampilkan semua data mahasiswa"];
            $result['total'] = count($data); $result['result'] = $data;
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal mengambil data: " . $conn->error];
        }
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $nim = $conn->real_escape_string($data['nim'] ?? ($_POST['nim'] ?? ''));
    $nama_mahasiswa = $conn->real_escape_string($data['nama_mahasiswa'] ?? ($_POST['nama_mahasiswa'] ?? ''));
    if (empty($nim) || empty($nama_mahasiswa)) {
        $result['status'] = ["code" => 400, "description" => "Field nim dan nama_mahasiswa wajib diisi"];
    } else {
        if ($conn->query("INSERT INTO dim_mahasiswa (nim, nama_mahasiswa) VALUES ('$nim', '$nama_mahasiswa')")) {
            $result['status'] = ["code" => 201, "description" => "1 data mahasiswa berhasil ditambahkan"];
            $result['result'] = ["id_mahasiswa" => $conn->insert_id, "nim" => $nim, "nama_mahasiswa" => $nama_mahasiswa];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal menambahkan data"];
        }
    }
} elseif ($method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) parse_str(file_get_contents("php://input"), $data);
    $id_mahasiswa = $conn->real_escape_string($data['id_mahasiswa'] ?? '');
    $nim = $conn->real_escape_string($data['nim'] ?? '');
    $nama_mahasiswa = $conn->real_escape_string($data['nama_mahasiswa'] ?? '');
    if (empty($id_mahasiswa) || empty($nim) || empty($nama_mahasiswa)) {
        $result['status'] = ["code" => 400, "description" => "Semua field wajib diisi"];
    } else {
        if ($conn->query("UPDATE dim_mahasiswa SET nim='$nim', nama_mahasiswa='$nama_mahasiswa' WHERE id_mahasiswa='$id_mahasiswa'")) {
            $result['status'] = ["code" => 200, "description" => "Data berhasil diupdate"];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal mengupdate data"];
        }
    }
} elseif ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) parse_str(file_get_contents("php://input"), $data);
    $id_mahasiswa = $conn->real_escape_string($data['id_mahasiswa'] ?? ($_GET['id_mahasiswa'] ?? ''));
    if (empty($id_mahasiswa)) {
        $result['status'] = ["code" => 400, "description" => "id_mahasiswa wajib diisi"];
    } else {
        if ($conn->query("DELETE FROM dim_mahasiswa WHERE id_mahasiswa='$id_mahasiswa'")) {
            $result['status'] = ["code" => 200, "description" => "Data berhasil dihapus"];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal menghapus data"];
        }
    }
} else {
    $result['status'] = ["code" => 405, "description" => "Method tidak diizinkan"];
}
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$conn->close();
?>
