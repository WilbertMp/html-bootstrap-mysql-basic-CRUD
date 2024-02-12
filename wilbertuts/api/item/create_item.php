<?php
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("X-Content-Type-Options: nosniff");
include '../koneksi.php';

$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$harga = $_POST['harga'];
$gambar = $_FILES['gambar']['name'];
$akunid = $_POST['akunid'];
$timestamp = time();

$uploadDirectory = '../../asset/upload/';
$uploadedFileName = $uploadDirectory . $timestamp . basename($gambar);

$imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

try {
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo json_encode(['status' => 'error', 'message' => 'Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.']);
        die("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
    }
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadedFileName)) {
        $sql = "INSERT INTO item (id, gambar, judul, deskripsi, harga, akunid) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $db_connection->prepare($sql);
        $statement->execute([null, $timestamp.$gambar, $judul, $deskripsi, $harga, $akunid]);

        if ($statement->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Item berhasil diupload dan data berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error menambah data']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error moving uploaded file']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'General error' . $e->getMessage()]);
}
