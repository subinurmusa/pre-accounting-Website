

<?php
require "db.php";

// Get current week's start and end dates
$currentWeekStartDate = date('Y-m-d', strtotime('monday this week'));
$currentWeekEndDate = date('Y-m-d', strtotime('sunday this week'));

// Function to fetch expenses from bankagiderler table for the current week
function getBankaGiderlerExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(STR_TO_DATE(issueDate, '%d.%m.%Y')) AS day, SUM(totalCost) AS total_expense FROM bankagiderler WHERE STR_TO_DATE(issueDate, '%d.%m.%Y') BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(STR_TO_DATE(issueDate, '%d.%m.%Y'))";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $expenses[$row['day']] = $row['total_expense'];
    }

    return $expenses;
}

// Function to fetch expenses from fisfaturagiderler table for the current week
function getFisFaturaGiderlerExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(STR_TO_DATE(fisFaturaDate, '%d.%m.%Y')) AS day, SUM(geneltoplam) AS total_expense FROM fisfaturagiderler WHERE STR_TO_DATE(fisFaturaDate, '%d.%m.%Y') BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(STR_TO_DATE(fisFaturaDate, '%d.%m.%Y'))";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $expenses[$row['day']] = $row['total_expense'];
    }

    return $expenses;
}

// Function to fetch expenses from maas table for the current week
function getMaasExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(date) AS day, SUM(toplamtutar) AS total_expense FROM maas WHERE date BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(date)";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $expenses[$row['day']] = $row['total_expense'];
    }

    return $expenses;
}

// Function to fetch expenses from vergisgkpirimigiderler table for the current week
function getVergisGkPirimGiderlerExpenses($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(date) AS day, SUM(totalCost) AS total_expense FROM vergisgkpirimigiderler WHERE date BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(date)";
    $result = $db->query($sql);

    $expenses = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $expenses[$row['day']] = $row['total_expense'];
    }

    return $expenses;
}

// Function to fetch income from selling table for the current week// Function to fetch income from selling table for the current week
function getIncome($db, $startOfWeek, $endOfWeek) {
    $sql = "SELECT DAYNAME(STR_TO_DATE(`date-added`, '%d.%m.%Y')) AS day, SUM(totalPrice) AS total_income FROM selling WHERE STR_TO_DATE(`date-added`, '%d.%m.%Y') BETWEEN '$startOfWeek' AND '$endOfWeek' GROUP BY DAYNAME(STR_TO_DATE(`date-added`, '%d.%m.%Y'))";
    $result = $db->query($sql);

    $income = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $income[$row['day']] = $row['total_income'];
    }

    return $income;
}
// Function to generate an array of week days and dates
// Function to generate an array of week days and dates in Turkish language
// Function to generate an array of week days and dates in Turkish language
// Function to generate an array of week days and dates in Turkish language
function generateWeekLabels($startOfWeek, $endOfWeek) {
    setlocale(LC_TIME, 'tr_TR.UTF-8'); // Set locale to Turkish

    // Define short week day names in Turkish
    $shortWeekDays = [
        'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cts', 'Paz'
    ];

    $labels = [];
    $currentDate = $startOfWeek;

    while ($currentDate <= $endOfWeek) {
        $weekDay = strftime('%a', strtotime($currentDate)); // Get short week day name
        $date = strftime('%d.%m.%y', strtotime($currentDate)); // Change format to day.month.year
        $labels[] = ['weekDay' => $shortWeekDays[date('N', strtotime($currentDate)) - 1], 'date' => $date];
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }

    return $labels;
}

// Generate array of week labels
$weekData = generateWeekLabels($currentWeekStartDate, $currentWeekEndDate);




// Get current week's expenses and income
$bankaGiderlerExpenses = getBankaGiderlerExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$fisFaturaGiderlerExpenses = getFisFaturaGiderlerExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$maasExpenses = getMaasExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$vergisGkPirimGiderlerExpenses = getVergisGkPirimGiderlerExpenses($db, $currentWeekStartDate, $currentWeekEndDate);
$income = getIncome($db, $currentWeekStartDate, $currentWeekEndDate);

// Close connection
$db = null;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haftalık Gelir ve Giderler</title>
    <!-- Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        #sidebar {
            width: 200px; /* Adjust the width of the sidebar */
            height: 100vh; /* Make the sidebar full height */
            background-color: #f1f1f1; /* Sidebar background color */
            position: fixed;
            top: 0;
            left: 0;
        }
        #content {
            margin-left: 200px; /* Adjust the margin to fit the sidebar */
            padding: 20px;
        }
        #myChart {
            width: 80%; /* Adjust the width of the chart */
            max-width: 800px; /* Set maximum width */
            margin: 20px auto; /* Center the chart horizontally */
            display: block;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <!-- Your sidebar content here -->
    </div>
    <div id="content">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_map(function($data) {
                return $data['weekDay'] . "\n" . $data['date']; // Combine week day and date with a line break
            }, $weekData)); ?>,



                datasets: [{
                    label: 'Banka Giderleri',
                    data: [<?php echo $bankaGiderlerExpenses['Monday']; ?>, <?php echo $bankaGiderlerExpenses['Tuesday']; ?>, <?php echo $bankaGiderlerExpenses['Wednesday']; ?>, <?php echo $bankaGiderlerExpenses['Thursday']; ?>, <?php echo $bankaGiderlerExpenses['Friday']; ?>, <?php echo $bankaGiderlerExpenses['Saturday']; ?>, <?php echo $bankaGiderlerExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Fis Fatura Giderleri',
                    data: [<?php echo $fisFaturaGiderlerExpenses['Monday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Tuesday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Wednesday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Thursday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Friday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Saturday']; ?>, <?php echo $fisFaturaGiderlerExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Maaş Giderleri',
                    data: [<?php echo $maasExpenses['Monday']; ?>, <?php echo $maasExpenses['Tuesday']; ?>, <?php echo $maasExpenses['Wednesday']; ?>, <?php echo $maasExpenses['Thursday']; ?>, <?php echo $maasExpenses['Friday']; ?>, <?php echo $maasExpenses['Saturday']; ?>, <?php echo $maasExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }, {
                    label: 'Vergi ve G.K. Prim Giderleri',
                    data: [<?php echo $vergisGkPirimGiderlerExpenses['Monday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Tuesday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Wednesday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Thursday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Friday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Saturday']; ?>, <?php echo $vergisGkPirimGiderlerExpenses['Sunday']; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Satış Gelirleri',
                    data: [<?php echo $income['Monday']; ?>, <?php echo $income['Tuesday']; ?>, <?php echo $income['Wednesday']; ?>, <?php echo $income['Thursday']; ?>, <?php echo $income['Friday']; ?>, <?php echo $income['Saturday']; ?>, <?php echo $income['Sunday']; ?>],
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Haftalık Gelir ve Giderler'
                    },
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 16
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
