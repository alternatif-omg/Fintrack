<?php
// Koneksi ke database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fintrack_app";
$port = "3306";

$connect = mysqli_connect ($servername, $username, $password, $dbname, $port);

// Periksa koneksi
if ($connect->connect_error) {
    die("Koneksi gagal: " . $connect->connect_error);
}

