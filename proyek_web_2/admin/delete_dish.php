<?php
// delete_dish.php
require_once '../config/database.php';
require_once '../includes/functions.php';
check_login();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama gambar dari database
    $sql = "SELECT image FROM dishes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dish = $result->fetch_assoc();
        $image_path = '../assets/Images/dishes/' . $dish['image'];

        // Hapus gambar dari server
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Hapus data dari database
        $sql_delete = "DELETE FROM dishes WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            set_flash_message('success', 'Makanan/Minuman berhasil dihapus.');
        } else {
            set_flash_message('error', 'Gagal menghapus makanan/minuman.');
        }
    } else {
        set_flash_message('error', 'Data tidak ditemukan.');
    }

    // Redirect back to dishes page
    header('Location: dishes.php');
    exit();
}
?>
