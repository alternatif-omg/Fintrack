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

// Ambil semua data cicilan
// Fetch existing cicilan data excluding those with status 'Lunas'
$sql = "SELECT * FROM cicilan WHERE id_pengguna='$id_pengguna' AND (status IS NULL OR status != 'Lunas')";
$result = $connect->query($sql);


// Inisialisasi variabel untuk total hutang
$total_hutang = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hutang</title>
    <link rel="stylesheet" href="pemasukan.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <!-- Tambahkan stylesheet CSS Anda di sini -->
    <style>
        body {
            font-family: 'Quicksand', sans-serif; /* Menggunakan Quicksand sebagai font utama, dengan fallback ke font sans-serif jika Quicksand tidak tersedia */
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }
        .content {
    
    margin-right: auto; /* Menggeser ke kanan */
    width: 80%;          /* Contoh lebar div */
    padding: 20px;       /* Contoh padding */
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

        .total-hutang {
            text-align: left;
            margin-top: 20px;
            font-weight: bold;
            
        }

        
    </style>
    <!-- Tambahkan script jQuery di sini -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            
            <h2>Daftar Cicilan</h2>
            <table border="1">
                <tr>
                    <th>Nominal</th>
                    <th>Tenggat Pembayaran</th>
                    <th>Sisa Hari</th>
                    <th>Keterangan</th>
                    <th>Aksi</th> <!-- Tambah kolom untuk tombol pembayaran -->
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['nominal']; ?></td>
                        <td><?php 
                            // Hitung tanggal tenggat pembayaran
                            $tenggat = date('Y-m-d', strtotime($row['tenggat'] . ' + 5 days'));
                            echo $tenggat; 
                        ?></td>
                        <td><?php 
                            // Hitung selisih hari
                            $today = date('Y-m-d');
                            $sisa_hari = (strtotime($tenggat) - strtotime($today)) / (60 * 60 * 24);
                            echo $sisa_hari; 
                        ?></td>
                        <td><?php echo $row['keterangan']; ?></td>
                        <td><a href="pembayaran.php?id_cicilan=<?php echo $row['id_cicilan']; ?>">Bayar</a></td> <!-- Tambahkan tombol untuk menuju pembayaran.php dengan membawa id_cicilan -->
                    </tr>
                    <?php
                    // Hitung total hutang
                    $total_hutang += $row['nominal'];
                    ?>
                <?php endwhile; ?>
            </table>

            <div class="total-hutang">
                <h2>Total Hutang: <?php echo $total_hutang; ?></h2>
            </div>
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
        <?php if (isset($_SESSION['notification_type']) && isset($_SESSION['notification_message'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['notification_type']; ?>',
            title: '<?php echo $_SESSION['notification_message']; ?>',
        });
        <?php unset($_SESSION['notification_type']); unset($_SESSION['notification_message']); ?>
    <?php endif; ?>
    </script>
</body>
</html>
