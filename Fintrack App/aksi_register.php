<?php
include "Connect.php";

// Memeriksa apakah ada data yang dikirim dari formulir

    // Get data from POST request
    $user = $_POST['username'];
    $email = $_POST['email'];
    $psw = $_POST['password'];
    

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO register (username, email, password) VALUES ('".$user."','".$email."','".$psw."' )";
    $query=$connect->query($sql);
    if ($query === true){
        header('location: form_login.php');

    }else{
        echo "erroooor";
    }
    
?>
