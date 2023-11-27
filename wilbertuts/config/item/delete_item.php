<?php
require_once('../../koneksi/koneksi.php');

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;

try {
    $sql = 'DELETE FROM item WHERE id = ?';
    $connect = $db_connection->prepare($sql);
    $connect->execute([$id]);
    echo json_encode(['success' => true]);
    header("Refresh:0");
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
