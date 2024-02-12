<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$akunid = $_POST['akunid'];

try {
    if (isset($akunid)) {
        $sql = "SELECT * FROM item WHERE akunid = ?";
        $statement = $db_connection->prepare($sql);
        $statement->execute([$akunid]);

        $item = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($item, $row);
        }

        echo json_encode(['status' => 'success', 'item' => $item]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'akun id kosong : ' . $akunid]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid : ' . $e]);
}
