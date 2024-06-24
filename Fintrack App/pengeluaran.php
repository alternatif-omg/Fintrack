<?php
session_start();
include "Connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_income'])) {
    // Tangkap data yang dikirimkan oleh form
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $date = date('Y-m-d', strtotime($_POST['date']));
    $description = $_POST['description'];

    if (isset($_SESSION['id_pengguna'])) {
        $id_pengguna = $_SESSION['id_pengguna'];

        // Simpan data ke database
        $sql = "INSERT INTO pengeluaran (judul, nominal, tanggal, keterangan, id_pengguna) VALUES ('$title', '$amount', '$date', '$description', '$id_pengguna')";
        $query = $connect->query($sql);

        if ($query === TRUE) {
            // Setelah data tersimpan, alihkan pengguna ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $connect->error;
        }
    } else {
        echo "Error: User not logged in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengeluaran</title>
    <link rel="stylesheet" href="pengeluaran.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Quicksand', sans-serif; /* Menggunakan Quicksand sebagai font utama, dengan fallback ke font sans-serif jika Quicksand tidak tersedia */
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav id="sidebar">
            <div class="sidebar-header">
                <nav class="sidebar">
                    <button id="dashboard">
                        <img src="Group 8735.png" alt="home Icon">
                    </button>
                    <button id="addIncome">
                        <img src="Group 8742.png" alt="Pemasukan Icon">
                    </button>
                    <button id="addExpense">
                        <img src="Group 8738.png" alt="Pengeluaran Icon">
                    </button>
                    <button id="transferBalance">
                        <img src="Group 8740.png" alt="Cicilan Icon">
                    </button>
                    <button id="result">
                        <img src="Group 8737.png" alt="Rekapan Icon">
                    </button>
                </nav>
            </div>
        </nav>
        <form action="pengeluaran.php" method="POST">
            <h1>Input Pengeluaran</h1>
            <label for="judul">Judul:</label>
            <input type="text" name="title" required>
            <label for="nominal">Nominal:</label>
            <input type="number" name="amount" required>
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="date" required>
            <label for="keterangan">Keterangan:</label>
            <textarea name="description" rows="4" cols="50"></textarea>
            <input type="submit" name="submit_income" value="Submit">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });

        document.getElementById("dashboard").addEventListener("click", function() {
            window.location.href = "dashboard.php";
        });
        document.getElementById("addIncome").addEventListener("click", function() {
            window.location.href = "pemasukan.php";
        });
        document.getElementById("addExpense").addEventListener("click", function() {
            window.location.href = "pengeluaran.php";
        });
        document.getElementById("transferBalance").addEventListener("click", function() {
            window.location.href = "cicilan.php";
        });
        document.getElementById("result").addEventListener("click", function() {
            window.location.href = "rekapan.php";
        });
    </script>
</body>
</html>
