<?php
header("Access-Control-Allow-Origin: *");
include '../koneksi.php';

$status = 'error';
$message = 'no message';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['session'])) {
        $session = $_POST['session'];
        $checkPasswordQuery = "SELECT * FROM akun WHERE session_token = ?";
        $stmt = $db_connection->prepare($checkPasswordQuery);
        $stmt->execute([$session]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $user['password'];
            $oldPassword = sha1($_POST['passwordLama']);

            if ($hashedPassword === $oldPassword) {
                $updateQuery = "UPDATE akun SET";
                $params = array();
                $values = array();

                if (!empty($_POST['username'])) {
                    $updateQuery .= " username = :username,";
                    $params[':username'] = $_POST['username'];
                }
                if (!empty($_POST['name'])) {
                    $updateQuery .= " nama = :name,";
                    $params[':name'] = $_POST['name'];
                }
                if (!empty($_POST['email'])) {
                    $updateQuery .= " email = :email,";
                    $params[':email'] = $_POST['email'];
                }
                if (!empty($_POST['passwordBaru'])) {
                    $updateQuery .= " password = :passwordBaru,";
                    $params[':passwordBaru'] = $_POST['passwordBaru'];
                }
                $updateQuery = rtrim($updateQuery, ",");

                $updateQuery .= " WHERE session_token = :session";
                $params[':session'] = $session;

                $stmt = $db_connection->prepare($updateQuery);
                foreach ($params as $param => $value) {
                    $stmt->bindValue($param, $value, PDO::PARAM_STR);
                }

                if ($stmt->execute()) {
                    $status = "success";
                    $message = "Data pengguna berhasil diperbarui.";
                } else {
                    $status = "error";
                    $message = "Terjadi kesalahan saat memperbarui data pengguna: " . $stmt->errorInfo();
                }
            } else {
                if ($_POST['passwordLama'] == null) {
                    $status = "error";
                    $message = "Password lama salah.";
                } else {
                    $status = "error";
                    $message = "Password lama tidak cocok.";
                }
            }
        } else {
            $status = "error";
            $message = "Sesi tidak valid.";
        }
    } else {
        $status = "error";
        $message = "Sesi tidak tersedia.";
    }
} else {
    $status = "error";
    $message = "Metode request tidak valid.";
}

echo json_encode(array("status" => $status, "message" => $message));
