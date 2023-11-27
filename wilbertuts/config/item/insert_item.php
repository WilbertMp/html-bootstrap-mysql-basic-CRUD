<?php
require('../../koneksi/koneksi.php');

$item_judul = $_POST['judul'];
$item_deskripsi = $_POST['deskripsi'];
$item_harga = $_POST['harga'];

$uploadDir = '../../image/';
$uploadFile = $uploadDir . basename($_FILES['gambar']['name']);
$item_gambar = $_FILES['gambar']['name'];

$imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
    die("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
}

if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
    try {
        $sql = 'INSERT INTO item (id, gambar, judul, deskripsi, harga) VALUES (?,?,?,?,?)';
        $connect = $db_connection->prepare($sql);
        $connect->execute([null, $item_gambar, $item_judul, $item_deskripsi, $item_harga]);

        header("Location: ../../main.php");
        exit();
    } catch (PDOException $e) {
        echo "gambar $item_gambar";
        die("Error memasukkan data : " . $e->getMessage());
    }
} else {
    die("Gagal mengunggah file gambar.");
}