<?php
// connect.php

// Start session
session_start();


// INSERT INTO user (username, password, fullname, email, role) VALUES
// ('admin', MD5('admin123'), 'Quản Trị Viên', 'admin@example.com', 'admin'),
// ('user1', MD5('user123'), 'Người Dùng 1', 'user1@example.com', 'user');

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'QL_NhanSu';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Set character set to UTF-8 to support Vietnamese characters
$conn->set_charset("utf8");
?>