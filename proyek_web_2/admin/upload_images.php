<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = '../assets/Images/uploads/';
    
    // Upload file
    if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
        // Simpan info gambar ke database
        $caption = basename($file_name); // Atau ambil dari input lain
        $query = "INSERT INTO gallery (file_name, caption) VALUES ('$file_name', '$caption')";
        query($query);
        header('Location: index.php'); // Redirect ke index setelah upload
        exit;
    } else {
        echo "Gagal mengupload gambar.";
    }
}
?>
