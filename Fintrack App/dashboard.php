<?php
session_start();
include "Connect.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: form_login.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];

// Pemeriksaan filter tanggal
$date_filter = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Pastikan format tanggal valid
$date_filter = date('Y-m-d', strtotime($date_filter));

// Ambil semua data pemasukan berdasarkan tanggal
$sql_all_income = "
    SELECT * 
    FROM pemasukan 
    WHERE id_pengguna = ? AND tanggal = ?";
$stmt_all_income = $connect->prepare($sql_all_income);
$stmt_all_income->bind_param("is", $id_pengguna, $date_filter);
$stmt_all_income->execute();
$result_all_income = $stmt_all_income->get_result();
$all_income_data = [];
while ($row = $result_all_income->fetch_assoc()) {
    $all_income_data[] = $row;
}
$stmt_all_income->close();



// Ambil semua data pengeluaran berdasarkan tanggal
$sql_all_expense = "
    SELECT * 
    FROM pengeluaran 
    WHERE id_pengguna = ? AND tanggal = ?";
$stmt_all_expense = $connect->prepare($sql_all_expense);
$stmt_all_expense->bind_param("is", $id_pengguna, $date_filter);
$stmt_all_expense->execute();
$result_all_expense = $stmt_all_expense->get_result();
$all_expense_data = [];
while ($row = $result_all_expense->fetch_assoc()) {
    $all_expense_data[] = $row;
}
$stmt_all_expense->close();



// Ambil total pemasukan berdasarkan tanggal
$sql_total_income = "
    SELECT SUM(nominal) AS total_income 
    FROM pemasukan 
    WHERE id_pengguna = ? AND tanggal = ?";
$stmt_total_income = $connect->prepare($sql_total_income);
$stmt_total_income->bind_param("is", $id_pengguna, $date_filter);
$stmt_total_income->execute();
$result_total_income = $stmt_total_income->get_result();
$total_income = $result_total_income->fetch_assoc()['total_income'] ?? 0;
$stmt_total_income->close();



// Ambil total pengeluaran berdasarkan tanggal
$sql_total_expense = "
    SELECT SUM(nominal) AS total_expense 
    FROM pengeluaran 
    WHERE id_pengguna = ? AND tanggal = ?";
$stmt_total_expense = $connect->prepare($sql_total_expense);
$stmt_total_expense->bind_param("is", $id_pengguna, $date_filter);
$stmt_total_expense->execute();
$result_total_expense = $stmt_total_expense->get_result();
$total_expense = $result_total_expense->fetch_assoc()['total_expense'] ?? 0;
$stmt_total_expense->close();



// Hitung selisih
$balance = $total_income - $total_expense;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Quicksand', sans-serif; /* Menggunakan Quicksand sebagai font utama, dengan fallback ke font sans-serif jika Quicksand tidak tersedia */
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
        }
        .details {
    display: flex;
    justify-content: space-around;
    margin: 20px;
    padding: 50px;
    width: 1100px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Section Styling */
.income-section, .expense-section {
    width: 80%; /* Adjusted width for wider sections */
}

.item {
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fafafa;
    transition: background-color 0.3s ease;
}

.item:hover {
    background-color: #f1f1f1;
}

/* Text Styling */
.title {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 5px;
}

.nominal {
    font-size: 1.1em;
    color: #333;
}

.income {
    color: green;
}

.expense {
    color: red;
}

.keterangan {
    font-size: 0.9em;
    color: #666;
    margin-top: 5px;
}

/* Responsive Design */
@media (max-width: 1068px) {
    .details {
        flex-direction: column;
        align-items: center;
    }

    .income-section, .expense-section {
        width: 80%; /* Adjusted width for narrower screens */
    }
}
#sidebar {
   width: 1250px; /* Adjust the width as needed */
   background-color: #333;
   padding: 20px;
   color: #fff;
   height:1000px;
   
}


.sidebar-header {
    padding: 10px;
    text-align: center;
}
.sidebar button {
    display: block;
    width: 100%;
    padding: 15px 0;
    background: none;
    border: none;
    cursor: pointer;
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
            <h1>Dashboard</h1>
            <form method="GET" action="dashboard.php">
                <label for="datepicker">Pilih Tanggal:</label>
                <input type="text" id="datepicker" name="date" value="<?php echo htmlspecialchars($date_filter); ?>" readonly>
                <button type="submit">Filter</button>
            </form>
            <div class="dashboard">
                <div class="info">
                    <div class="info-container">
                        <img src="1.png" alt="masuk Icon">
                        <div class="info-text"><?php echo number_format($total_income, 0, ',', '.'); ?></div>
                    </div>
                    <div class="info-container">
                        <img src="12.png" alt="pengeluaran Icon">
                        <div class="info-text"><?php echo number_format($total_expense, 0, ',', '.'); ?></div>
                    </div>
                    <div class="info-container">
                        <img src="13.png" alt="selisih Icon">
                        <div class="info-text"><?php echo number_format($balance, 0, ',', '.'); ?></div>
                    </div>
                </div>
                <div class="details">
    <div class="income-section">
        <?php foreach ($all_income_data as $income): ?>
            <div class="item">
                <div class="title"><?php echo htmlspecialchars($income['judul']); ?></div>
                <div class="nominal income" data-sign="+"><?php echo number_format($income['nominal'], 0, ',', '.'); ?></div>
                <div class="keterangan"><?php echo htmlspecialchars($income['keterangan']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="expense-section">
        <?php foreach ($all_expense_data as $expense): ?>
            <div class="item">
                <div class="title"><?php echo htmlspecialchars($expense['judul']); ?></div>
                <div class="nominal expense" data-sign="-"><?php echo number_format($expense['nominal'], 0, ',', '.'); ?></div>
                <div class="keterangan"><?php echo htmlspecialchars($expense['keterangan']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</div>

            </div>
        </div>
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
