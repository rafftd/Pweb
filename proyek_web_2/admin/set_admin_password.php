<?php
require_once '../config/database.php';

// Password yang ingin diset
$password = "admin123"; // Ganti dengan password yang diinginkan

// Generate password hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update password admin
$sql = "UPDATE admin SET password = ? WHERE username = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "Password admin berhasil diupdate!<br>";
    echo "Username: admin<br>";
    echo "Password: " . $password . "<br>";
    echo "Silahkan hapus file ini setelah digunakan untuk keamanan.";
} else {
    echo "Error updating password: " . $conn->error;
}

$stmt->close();
$conn->close();