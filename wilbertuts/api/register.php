<?php
header("Access-Control-Allow-Origin: *");
include 'koneksi.php';

$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];
$name = $_POST["name"];

if (isset($username) && isset($password)) {
    $sql = "INSERT INTO `akun`(`id`, `username`, `password`, `email`, `nama`, `session_token`) VALUES (?,?,?,?,?,?)";
    $statement = $db_connection->prepare($sql);
    $statement->execute([null, $username, sha1($password), $email, $name, null]);

    if ($statement->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registrasi Gagal']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Request Tidak Valid']);
}
