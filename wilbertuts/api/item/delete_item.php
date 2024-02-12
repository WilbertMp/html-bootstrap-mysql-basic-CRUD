<?php
require_once('../koneksi.php');

$id = $_POST['id'];
$gambar = $_POST['gambar'];
$uploadDir = '../../asset/upload/';
$imageDir = $uploadDir . $gambar;

try {
    $sql = 'DELETE FROM item WHERE id = ?';
    $connect = $db_connection->prepare($sql);
    $connect->execute([$id]);
    if (file_exists($imageDir)) {
        unlink($imageDir);
        if ($connect->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Sukses menghapus item']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus item']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus gambar']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'success', 'message' => $e->getMessage()]);
}
