<?php
session_start();
include "Connect.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: form_login.php");
    exit();
}

// Tangkap id_pengguna dari sesi
$id_pengguna = $_SESSION['id_pengguna'];

// Pesan kesalahan awalnya kosong
$error_message = "";

// Tangkap data yang dikirimkan oleh form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_payment'])) {
    $nominal = $_POST['nominal'];
    $tenggat = $_POST['tenggat'];
    $keterangan = $_POST['keterangan'];

    // Validasi data (contoh: nominal harus lebih dari 0)
    if ($nominal <= 0) {
        $error_message = "Nominal harus lebih dari 0.";
    } else {
        // Jika data valid, simpan ke database
        $sql = "INSERT INTO cicilan (id_pengguna, nominal, tenggat, keterangan) VALUES ('$id_pengguna', '$nominal', '$tenggat', '$keterangan')";
        $query = $connect->query($sql);

        if ($query === TRUE) {
            // Jika berhasil disimpan, alihkan ke halaman dashboard
            header("Location: form_hutang.php");
            exit();
        } else {
            // Jika terjadi kesalahan saat menyimpan ke database
            $error_message = "Gagal menyimpan data. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Cicilan</title>
    <link rel="stylesheet" href="cicilan.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <!-- Tambahkan stylesheet CSS Anda di sini -->
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
        <form action="cicilan.php" method="POST">
            <h1>Input Cicilan</h1>
            <label for="nominal">Nominal:</label>
            <input type="number" name="nominal" required>
            <label for="tenggat">Tenggat Pembayaran:</label>
            <input type="date" name="tenggat" required>
            <label for="keterangan">Keterangan:</label>
            <textarea name="keterangan" rows="4" cols="50"></textarea>
            <div class="form-buttons">
                <input type="submit" name="submit_payment" value="Submit">
                <button type="button" id="goToPembayaran">Daftar Cicilan</button>
            </div>
        </form>

        <!-- Tampilkan pesan kesalahan jika ada -->
        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
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
        document.getElementById("goToPembayaran").addEventListener("click", function() {
            window.location.href = "pembayaran.php";
        });
    </script>
</body>
</html>
