<?php
header("Access-Control-Allow-Origin: *");
include 'koneksi.php';

$session = $_POST["session"];

if (isset($session)) {
    $sql = "UPDATE akun SET session_token = NULL WHERE session_token = ?";
    $statement = $db_connection->prepare($sql);
    $statement->execute([$session]);
    $result = $statement->rowCount();

    if ($result > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Logout berhasil']);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Session tidak valid $result"]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}