<?php
header("Access-Control-Allow-Origin: *");
include 'koneksi.php';

$session = $_POST["session"];

if (isset($session)) {
    $sql = "DELETE FROM akun WHERE session_token = ?";
    $statement = $db_connection->prepare($sql);
    $statement->execute([$session]);

    if ($statement->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Sukses menghapus user']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus user']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'tidak ada session']);
}