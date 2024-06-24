<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter dan Hasil Rekapan</title>
    <link rel="stylesheet" href="rekap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tambahkan stylesheet CSS Anda di sini -->
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }

        .container {
            max-width: auto;
            margin: 20px auto;
            display: flex;
            flex-direction: row;
        }

        .result-title {
            text-align: center;
            color: #ffc400;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        #sidebar {
            width: 200px;
            background-color: #333;
            padding: 20px;
            color: #fff;
        }

        #content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 15px;
            margin-left: 20px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            max-width: 600px;
            margin: 0px 10px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        label {
            width: 100px;
            text-align: right;
            flex: 1;
            margin-bottom: 5px;
        }

        select, input[type="number"] {
            flex: 2;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #ffc300;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #ffc400;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .back-to-dashboard button {
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-to-dashboard button:hover {
            background-color: #555;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            margin-left: 20px;
            height: 100vh;
            overflow-y: auto;
        }

        .actions button {
            margin-bottom: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidebar button {
            background-color: transparent;
            border: none;
            color: #fff;
            padding: 10px 0;
            text-align: left;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .sidebar button img {
            margin-right: 10px;
            width: auto;
            height: auto;
        }

        .actions button:hover {
            background-color: #0056b3;
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

        <div id="content">
            <h2>Filter dan Hasil Rekapan</h2>
            
            <?php
// Include database connection file
include 'Connect.php';

// Initialize variables to hold form data
$bulan = $tahun = "";
$result = null;

// Initialize variables to hold aggregated data
$tot_pemasukan = $tot_pengeluaran = $tot_cicilan = $tot_pembayaran = 0;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['bulan']) && isset($_GET['tahun'])) {
    // Retrieve form data
    $bulan = $_GET['bulan'];
    $tahun = $_GET['tahun'];

    // Prepare SQL statement to fetch aggregated data
    $sql = "
        SELECT 
            MIN(tanggal_awal) AS tanggal_awal,
            MAX(tanggal_akhir) AS tanggal_akhir,
            SUM(tot_pemasukan) AS tot_pemasukan,
            SUM(tot_pengeluaran) AS tot_pengeluaran,
            SUM(tot_cicilan) AS tot_cicilan,
            SUM(tot_pembayaran) AS tot_pembayaran
        FROM rekapan
        WHERE bulan = ? AND tahun = ?
    ";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ii", $bulan, $tahun);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $tot_pemasukan = $result['tot_pemasukan'];
        $tot_pengeluaran = $result['tot_pengeluaran'];
        $tot_cicilan = $result['tot_cicilan'];
        $tot_pembayaran = $result['tot_pembayaran'];
    }


 // Close statement
 $stmt->close();
}

// Close connection
$connect->close();
?>

            <!-- Form untuk memilih bulan dan tahun -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <label for="bulan">Bulan:</label>
                <select name="bulan" id="bulan">
                    <!-- Opsi bulan -->
                    <option value="1" <?php if ($bulan == "1") echo "selected"; ?>>Januari</option>
                    <option value="2" <?php if ($bulan == "2") echo "selected"; ?>>Februari</option>
                    <option value="3" <?php if ($bulan == "3") echo "selected"; ?>>Maret</option>
                    <option value="4" <?php if ($bulan == "4") echo "selected"; ?>>April</option>
                    <option value="5" <?php if ($bulan == "5") echo "selected"; ?>>Mei</option>
                    <option value="6" <?php if ($bulan == "6") echo "selected"; ?>>Juni</option>
                    <option value="7" <?php if ($bulan == "7") echo "selected"; ?>>Juli</option>
                    <option value="8" <?php if ($bulan == "8") echo "selected"; ?>>Agustus</option>
                    <option value="9" <?php if ($bulan == "9") echo "selected"; ?>>September</option>
                    <option value="10" <?php if ($bulan == "10") echo "selected"; ?>>Oktober</option>
                    <option value="11" <?php if ($bulan == "11") echo "selected"; ?>>November</option>
                    <option value="12" <?php if ($bulan == "12") echo "selected"; ?>>Desember</option>
                </select>

                <label for="tahun">Tahun:</label>
                <input type="number" name="tahun" id="tahun" min="2000" max="2100" value="<?php echo $tahun; ?>" required>

                <button type="submit">Tampilkan Rekapan</button>
            </form>

            <!-- Tampilkan hasil pencarian jika ada -->
            <?php if ($result): ?>
                <div style="margin-top: 20px;">
                    <h3>Hasil Rekapan untuk Bulan <?php echo $bulan; ?> Tahun <?php echo $tahun; ?></h3>
                    <canvas id="myChart" width="200" height="100"></canvas>
                    <table>
                        <tr>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Akhir</th>
                            <th>Total Pemasukan</th>
                            <th>Total Pengeluaran</th>
                            <th>Total Cicilan</th>
                            <th>Total Pembayaran</th>
                        </tr>
                        <tr>
                            <td><?php echo $result['tanggal_awal']; ?></td>
                            <td><?php echo $result['tanggal_akhir']; ?></td>
                            <td><?php echo $result['tot_pemasukan']; ?></td>
                            <td><?php echo $result['tot_pengeluaran']; ?></td>
                            <td><?php echo $result['tot_cicilan']; ?></td>
                            <td><?php echo $result['tot_pembayaran']; ?></td>
                        </tr>
                    </table>
                </div>
            <?php elseif ($result !== null): ?>
                <p>No results found</p>
            <?php endif; ?>

            
        </div> <!-- End of #content -->
    </div> <!-- End of .container -->
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
         // Data dari PHP
    const totPemasukan = <?php echo $tot_pemasukan; ?>;
    const totPengeluaran = <?php echo $tot_pengeluaran; ?>;
    const totCicilan = <?php echo $tot_cicilan; ?>;
    const totPembayaran = <?php echo $tot_pembayaran; ?>;

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Pemasukan', 'Total Pengeluaran', 'Total Cicilan', 'Total Pembayaran'],
            datasets: [{
                label: 'Jumlah',
                data: [totPemasukan, totPengeluaran, totCicilan, totPembayaran],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>
</html>
