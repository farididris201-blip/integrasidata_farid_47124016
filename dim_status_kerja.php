<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'database.php';
$method = $_SERVER['REQUEST_METHOD'];
$result = [];

if ($method == 'GET') {
    if (isset($_GET['id_status_kerja'])) {
        $id = $conn->real_escape_string($_GET['id_status_kerja']);
        $query = $conn->query("SELECT * FROM dim_status_kerja WHERE id_status_kerja = '$id'");
        if ($query && $query->num_rows > 0) {
            $result['status'] = ["code" => 200, "description" => "Data ditemukan"];
            $result['result'] = $query->fetch_assoc();
        } else {
            $result['status'] = ["code" => 404, "description" => "Data tidak ditemukan"];
        }
    } elseif (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $query = $conn->query("SELECT * FROM dim_status_kerja WHERE status_kerja LIKE '%$search%'");
        if ($query && $query->num_rows > 0) {
            $data = []; while ($row = $query->fetch_assoc()) $data[] = $row;
            $result['status'] = ["code" => 200, "description" => "Data ditemukan"];
            $result['total'] = count($data); $result['result'] = $data;
        } else {
            $result['status'] = ["code" => 404, "description" => "Data tidak ditemukan"];
        }
    } else {
        $query = $conn->query("SELECT * FROM dim_status_kerja ORDER BY id_status_kerja ASC");
        if ($query) {
            $data = []; while ($row = $query->fetch_assoc()) $data[] = $row;
            $result['status'] = ["code" => 200, "description" => "Menampilkan semua data"];
            $result['total'] = count($data); $result['result'] = $data;
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal mengambil data: " . $conn->error];
        }
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $status_kerja = $conn->real_escape_string($data['status_kerja'] ?? ($_POST['status_kerja'] ?? ''));
    if (empty($status_kerja)) {
        $result['status'] = ["code" => 400, "description" => "Field status_kerja wajib diisi"];
    } else {
        if ($conn->query("INSERT INTO dim_status_kerja (status_kerja) VALUES ('$status_kerja')")) {
            $result['status'] = ["code" => 201, "description" => "1 data berhasil ditambahkan"];
            $result['result'] = ["id_status_kerja" => $conn->insert_id, "status_kerja" => $status_kerja];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal menambahkan data"];
        }
    }
} elseif ($method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) parse_str(file_get_contents("php://input"), $data);
    $id_status_kerja = $conn->real_escape_string($data['id_status_kerja'] ?? '');
    $status_kerja = $conn->real_escape_string($data['status_kerja'] ?? '');
    if (empty($id_status_kerja) || empty($status_kerja)) {
        $result['status'] = ["code" => 400, "description" => "Semua field wajib diisi"];
    } else {
        if ($conn->query("UPDATE dim_status_kerja SET status_kerja='$status_kerja' WHERE id_status_kerja='$id_status_kerja'")) {
            $result['status'] = ["code" => 200, "description" => "Data berhasil diupdate"];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal mengupdate data"];
        }
    }
} elseif ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) parse_str(file_get_contents("php://input"), $data);
    $id_status_kerja = $conn->real_escape_string($data['id_status_kerja'] ?? ($_GET['id_status_kerja'] ?? ''));
    if (empty($id_status_kerja)) {
        $result['status'] = ["code" => 400, "description" => "id_status_kerja wajib diisi"];
    } else {
        if ($conn->query("DELETE FROM dim_status_kerja WHERE id_status_kerja='$id_status_kerja'")) {
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
