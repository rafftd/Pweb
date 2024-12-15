<?php
// includes/functions.php

session_start();

// Function untuk upload gambar
function upload_image($file, $target_dir = "assets/Images/uploads/") {
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Cek if image file is actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ["error" => "File is not an image."];
    }
    
    // Cek file size
    if ($file["size"] > 500000) {
        return ["error" => "Sorry, your file is too large."];
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        return ["error" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."];
    }
    
    // Generate unique filename
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["success" => true, "filename" => $new_filename];
    } else {
        return ["error" => "Sorry, there was an error uploading your file."];
    }
}

// Function untuk check login
function check_login() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Function untuk set flash message
function set_flash_message($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Function untuk get flash message
function get_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Function untuk sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function untuk generate pagination
function generate_pagination($total_records, $records_per_page, $current_page) {
    $total_pages = ceil($total_records / $records_per_page);
    
    $pagination = '<div class="pagination">';
    
    if ($current_page > 1) {
        $pagination .= '<a href="?page='.($current_page-1).'">&laquo; Previous</a>';
    }
    
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $pagination .= '<span class="current">'.$i.'</span>';
        } else {
            $pagination .= '<a href="?page='.$i.'">'.$i.'</a>';
        }
    }
    
    if ($current_page < $total_pages) {
        $pagination .= '<a href="?page='.($current_page+1).'">Next &raquo;</a>';
    }
    
    $pagination .= '</div>';
    
    return $pagination;
}