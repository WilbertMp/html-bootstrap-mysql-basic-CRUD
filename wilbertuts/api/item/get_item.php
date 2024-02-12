<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$id = $_POST['itemId'];

try {
    if (isset($id)) {
        $sql = "SELECT * FROM item WHERE id = ?";
        $statement = $db_connection->prepare($sql);
        $statement->execute([$id]);

        $item = $statement->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'item' => $item]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'akun id kosong : ' . $akunid]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid : ' . $e]);
}
