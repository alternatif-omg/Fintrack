<?php
session_start();
include "Connect.php";

$user = $_POST['username'];
$psw = $_POST['password'];
$op = $_GET['op'];

if ($op == "in") {
    // Prepare the SQL statement with placeholders
    $sql = "SELECT * FROM register WHERE username = '$user'AND password='$psw'";
    $query =$connect->query($sql);
    if ($query->num_rows == 1) {
        $data = $query->fetch_array();
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_pengguna'] = $data['id_pengguna']; // Asumsi kolom id_pengguna ada di tabel register
        header("Location: dashboard.php");
        exit();
    }
}else if($op == "out"){
    unset($_SESSION['username']);
    unset($_SESSION['id_pengguna']);
    header("Location: form_login.php");
    exit();
}
?>

