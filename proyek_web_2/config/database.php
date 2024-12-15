<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'nusantara_cuisine');

// Buat koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset ke utf8
$conn->set_charset("utf8");

// Function untuk escape string
function escape_string($string) {
    global $conn;
    return $conn->real_escape_string($string);
}

// Function untuk query
function query($sql) {
    global $conn;
    return $conn->query($sql);
}

// Function untuk get single row
function fetch_assoc($result) {
    return $result->fetch_assoc();
}

// Function untuk get multiple rows
function fetch_all($result) {
    return $result->fetch_all(MYSQLI_ASSOC);
}