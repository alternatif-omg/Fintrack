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

// Tangkap id cicilan dari URL
if(isset($_GET['id_cicilan'])){
    $id_cicilan = $_GET['id_cicilan'];
}else{
    header("Location: form_hutang.php");
    exit();
}

// Ambil data cicilan berdasarkan id_cicilan
$sql = "SELECT * FROM cicilan WHERE id_cicilan = '$id_cicilan' AND id_pengguna = '$id_pengguna'";
$result = $connect->query($sql);
$row = $result->fetch_assoc();

// Jika data cicilan tidak ditemukan
if(!$row){
    header("Location: form_hutang.php");
    exit();
}

// Tangkap nilai hutang dari data cicilan
$nominal_hutang = $row['nominal'];

// Tangkap data pembayaran dari form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pembayaran'])) {
    $nominal_pembayaran = $_POST['nominal'];
    $tanggal_pembayaran = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // Memperbaiki query SQL untuk menyimpan data pembayaran
    $sql_pembayaran = "INSERT INTO pembayaran (id_cicilan, nominal, tanggal, keterangan) VALUES ('$id_cicilan', '$nominal_pembayaran', '$tanggal_pembayaran', '$keterangan')";

    // Eksekusi query SQL untuk menyimpan data pembayaran
    if ($connect->query($sql_pembayaran) === TRUE) {
        // Periksa apakah hutang sudah lunas
        if ($nominal_pembayaran >= $nominal_hutang) {
            // Jika nominal pembayaran lebih besar atau sama dengan hutang, update status cicilan menjadi lunas
            $sql_update = "UPDATE cicilan SET status = 'Lunas' WHERE id_cicilan = '$id_cicilan' AND id_pengguna = '$id_pengguna'";
            $connect->query($sql_update);

            // Inisialisasi variabel notifikasi
            $_SESSION['notification_type'] = "success";
            $_SESSION['notification_message'] = "Selamat, hutang Anda sudah lunas!";
        } else {
            // Jika nominal pembayaran kurang dari hutang, kurangi nilai hutang dengan nominal pembayaran
            $nominal_terhutang = $nominal_hutang - $nominal_pembayaran;

            // Ambil tanggal cicilan dari data cicilan
            $tanggal_cicilan = $row['tanggal'];

            // Hitung jumlah hari hingga tenggat waktu
            $tanggal_sekarang = new DateTime();
            $tanggal_tenggat = new DateTime($tanggal_cicilan);
            $interval = $tanggal_sekarang->diff($tanggal_tenggat);
            $hari_tersisa = $interval->days;

            $sql_update = "UPDATE cicilan SET nominal = '$nominal_terhutang' WHERE id_cicilan = '$id_cicilan' AND id_pengguna = '$id_pengguna'";
            $connect->query($sql_update);

            // Inisialisasi variabel notifikasi
            $_SESSION['notification_type'] = "warning";
            $_SESSION['notification_message'] = "Pembayaran berhasil. Hutang Anda masih kurang Rp $nominal_terhutang. Tenggat waktu pembayaran adalah $tanggal_cicilan, tersisa $hari_tersisa hari.";
        }

        // Redirect ke halaman yang sama setelah pembayaran
        header("Location: pembayaran.php?id_cicilan=$id_cicilan");
        exit();
    } else {
        echo "Error: " . $sql_pembayaran . "<br>" . $connect->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="pengeluaran.css">
    <!-- Tambahkan referensi ke jQuery, jQuery UI, dan SweetAlert -->
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }
        .content {
            margin-right: auto;
            width: 80%;
            padding: 20px;
        }
        h1, h2 {
            text-align: left;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ffbb00;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        
input[type="submit"] {
    background-color: orange;
    color: black;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 16px;
    display: inline-block;
    margin-right: 20px; /* Adjusted margin for spacing */
}

input[type="submit"]:hover {
    background-color: goldenrod;
}

form {
    text-align: left;
}

.button-container {
    display: flex; /* Menggunakan flexbox untuk tata letak */
    align-items: center; /* Posisikan item secara vertikal di tengah */
    margin-top: 20px;
}

.button-container button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.button-container button:hover {
    background-color: #0056b3;
}

.button-container a {
    margin-left: 20px; /* Menambahkan jarak antara tombol dan link */
}
    </style>
</head>
<body>
<div class="container">
    <div id="sidebar">
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
    </div>
    <div class="content">
        <h1>Pembayaran</h1>

        <!-- Notification Section -->
        <?php if (isset($_SESSION['notification_message'])): ?>
            <script>
                let notificationType = "<?php echo $_SESSION['notification_type']; ?>";
                let notificationMessage = "<?php echo $_SESSION['notification_message']; ?>";

                Swal.fire({
                    icon: notificationType,
                    title: notificationType === 'success' ? 'Success' : 'Warning',
                    text: notificationMessage
                });

                <?php unset($_SESSION['notification_message']); unset($_SESSION['notification_type']); ?>
            </script>
        <?php endif; ?>

        <form action="pembayaran.php?id_cicilan=<?php echo $id_cicilan; ?>" method="POST">
            <label for="nominal">Nominal Pembayaran:</label>
            <input type="number" name="nominal" value="<?php echo $nominal_hutang; ?>" required><br>
            <label for="tanggal">Tanggal Pembayaran:</label>
            <input type="date" name="tanggal" required><br>
            <label for="keterangan">Keterangan:</label><br>
            <textarea name="keterangan" rows="4" cols="50"></textarea><br>
            <div class="button-container">
            <input type="submit" name="submit_pembayaran" value="Bayar">
            
            
            <button onclick="window.location.href='form_hutang.php'">Daftar Cicilan</button>
        </div>
            
        </form>
    </div>
</div>
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
            window.location.href = "from_hutang.php";
        });
</script>
</body>
</html>
