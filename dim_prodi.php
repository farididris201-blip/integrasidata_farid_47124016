<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'database.php';
$method = $_SERVER['REQUEST_METHOD'];
$result = [];

if ($method == 'GET') {
    if (isset($_GET['id_prodi'])) {
        $id = $conn->real_escape_string($_GET['id_prodi']);
        $query = $conn->query("SELECT * FROM dim_prodi WHERE id_prodi = '$id'");
        if ($query && $query->num_rows > 0) {
            $result['status'] = ["code" => 200, "description" => "Data prodi ditemukan"];
            $result['result'] = $query->fetch_assoc();
        } else {
            $result['status'] = ["code" => 404, "description" => "Data prodi tidak ditemukan"];
        }
    } elseif (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $query = $conn->query("SELECT * FROM dim_prodi WHERE jurusan LIKE '%$search%' OR program_studi LIKE '%$search%'");
        if ($query && $query->num_rows > 0) {
            $data = []; while ($row = $query->fetch_assoc()) $data[] = $row;
            $result['status'] = ["code" => 200, "description" => "Data prodi ditemukan"];
            $result['total'] = count($data); $result['result'] = $data;
        } else {
            $result['status'] = ["code" => 404, "description" => "Data prodi tidak ditemukan"];
        }
    } else {
        $query = $conn->query("SELECT * FROM dim_prodi ORDER BY id_prodi ASC");
        if ($query) {
            $data = []; while ($row = $query->fetch_assoc()) $data[] = $row;
            $result['status'] = ["code" => 200, "description" => "Menampilkan semua data prodi"];
            $result['total'] = count($data); $result['result'] = $data;
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal mengambil data: " . $conn->error];
        }
    }
} elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $jurusan = $conn->real_escape_string($data['jurusan'] ?? ($_POST['jurusan'] ?? ''));
    $program_studi = $conn->real_escape_string($data['program_studi'] ?? ($_POST['program_studi'] ?? ''));
    if (empty($jurusan) || empty($program_studi)) {
        $result['status'] = ["code" => 400, "description" => "Field jurusan dan program_studi wajib diisi"];
    } else {
        if ($conn->query("INSERT INTO dim_prodi (jurusan, program_studi) VALUES ('$jurusan', '$program_studi')")) {
            $result['status'] = ["code" => 201, "description" => "1 data prodi berhasil ditambahkan"];
            $result['result'] = ["id_prodi" => $conn->insert_id, "jurusan" => $jurusan, "program_studi" => $program_studi];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal menambahkan data"];
        }
    }
} elseif ($method == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) parse_str(file_get_contents("php://input"), $data);
    $id_prodi = $conn->real_escape_string($data['id_prodi'] ?? '');
    $jurusan = $conn->real_escape_string($data['jurusan'] ?? '');
    $program_studi = $conn->real_escape_string($data['program_studi'] ?? '');
    if (empty($id_prodi) || empty($jurusan) || empty($program_studi)) {
        $result['status'] = ["code" => 400, "description" => "Semua field wajib diisi"];
    } else {
        if ($conn->query("UPDATE dim_prodi SET jurusan='$jurusan', program_studi='$program_studi' WHERE id_prodi='$id_prodi'")) {
            $result['status'] = ["code" => 200, "description" => "Data berhasil diupdate"];
        } else {
            $result['status'] = ["code" => 500, "description" => "Gagal mengupdate data"];
        }
    }
} elseif ($method == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) parse_str(file_get_contents("php://input"), $data);
    $id_prodi = $conn->real_escape_string($data['id_prodi'] ?? ($_GET['id_prodi'] ?? ''));
    if (empty($id_prodi)) {
        $result['status'] = ["code" => 400, "description" => "id_prodi wajib diisi"];
    } else {
        if ($conn->query("DELETE FROM dim_prodi WHERE id_prodi='$id_prodi'")) {
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
