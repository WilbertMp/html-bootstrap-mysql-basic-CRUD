<?php
header("Access-Control-Allow-Origin: *");
include 'koneksi.php';

$username = $_POST["username"];
$password = $_POST["password"];

if (isset($username) && isset($password)) {
    $sql = "SELECT id, username, password, nama FROM akun WHERE username = ?";
    $statement = $db_connection->prepare($sql);
    $statement->execute([$username]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && sha1($password) === $user['password']) {
        $session = bin2hex(random_bytes(16));

        $sql = "UPDATE akun SET session_token = ? WHERE id = ?";
        $updateStatement = $db_connection->prepare($sql);
        $updateStatement->execute([$session, $user['id']]);

        $_SESSION['session'] = $session;
        echo json_encode(['status' => 'success', 'session' => $session]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Kredensial tidak valid']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}
