<?php
require('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['id'];
    $item_judul = $_POST['judul'];
    $item_deskripsi = $_POST['deskripsi'];
    $item_harga = $_POST['harga'];
    $akunid = $_POST['akunid'];
    $prevGambar = $_POST['prevGambar'];
    $timestamp = time();

    if (isset($_FILES['gambar'])) {
        $uploadDir = '../../asset/upload/';
        $uploadFile = $uploadDir . $timestamp . basename($_FILES['gambar']['name']);
        $item_gambar = $_FILES['gambar']['name'];

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            echo json_encode(['status' => 'error', 'message' => 'Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.']);
            die("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
            try {
                $prevImagePath = $uploadDir . $prevGambar;
                if (file_exists($prevImagePath)) {
                    unlink($prevImagePath);
                }
                $sql = 'UPDATE item SET gambar = ?, judul = ?, deskripsi = ?, harga = ? WHERE id = ?';
                $connect = $db_connection->prepare($sql);
                $connect->execute([$timestamp . $item_gambar, $item_judul, $item_deskripsi, $item_harga, $itemId]);
                if ($connect->rowCount() > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Berhasil update data']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error update data']);
                }
                exit();
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error database' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error ketika upload gambar']);
        }
    } else {
        try {
            $sql = 'UPDATE item SET judul = ?, deskripsi = ?, harga = ? WHERE id = ?';
            $connect = $db_connection->prepare($sql);
            $connect->execute([$item_judul, $item_deskripsi, $item_harga, $itemId]);
            if ($connect->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Berhasil update data']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error update data']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error database' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error server bukan post' . $e->getMessage()]);
}

function updateItemWithImage($itemId, $item_gambar, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;
}

function updateItemWithoutImage($itemId, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;
}
