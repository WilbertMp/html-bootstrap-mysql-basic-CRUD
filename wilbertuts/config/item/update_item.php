<?php
require('../../koneksi/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['id'];
    $item_judul = $_POST['judul'];
    $item_deskripsi = $_POST['deskripsi'];
    $item_harga = $_POST['harga'];

    if ($_FILES['gambar']['size'] > 0) {
        $uploadDir = '../../image/';
        $uploadFile = $uploadDir . basename($_FILES['gambar']['name']);
        $item_gambar = $_FILES['gambar']['name'];

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            alert("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
            die("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
            alert("update gambar pake gambar sebelum masuk fungsi");
            updateItemWithImage($itemId, $item_gambar, $item_judul, $item_deskripsi, $item_harga);
        } else {
            alert("Gagal mengunggah file gambar");

            die("Gagal mengunggah file gambar.");
        }
    } else {
        alert("update gambar gapake gambar sebelum masuk fungsi");
        updateItemWithoutImage($itemId, $item_judul, $item_deskripsi, $item_harga);
    }
} else {
    alert("masuk else post");

    header("Location: ../../main.php");
    exit();
}

function updateItemWithImage($itemId, $item_gambar, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;

    try {
        $sql = 'UPDATE item SET gambar = ?, judul = ?, deskripsi = ?, harga = ? WHERE id = ?';
        $connect = $db_connection->prepare($sql);
        $connect->execute([$item_gambar, $item_judul, $item_deskripsi, $item_harga, $itemId]);

        header("Location: ../../main.php");
        alert("update pake gambar");
        exit();
    } catch (PDOException $e) {
        die("Error memperbarui data : " . $e->getMessage());
    }
}

function updateItemWithoutImage($itemId, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;

    try {
        $sql = 'UPDATE item SET judul = ?, deskripsi = ?, harga = ? WHERE id = ?';
        $connect = $db_connection->prepare($sql);
        $connect->execute([$item_judul, $item_deskripsi, $item_harga, $itemId]);
        alert("update pake gambar");

        header("Location: ../../main.php");
        exit();
    } catch (PDOException $e) {
        die("Error memperbarui data : " . $e->getMessage());
    }
}
