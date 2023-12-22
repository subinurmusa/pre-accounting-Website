<?php
require "db.php"; // Assuming db.php contains your database connection code

// Fetch data for the current week
$startDate = date("Y-m-d", strtotime("last monday"));
$endDate = date("Y-m-d", strtotime("next sunday"));

$sql = $db->prepare("SELECT date_added, SUM(price) AS total_price FROM selling WHERE date_added BETWEEN :start_date AND :end_date GROUP BY date_added");
$sql->bindParam(':start_date', $startDate);
$sql->bindParam(':end_date', $endDate);

if ($sql->execute()) {
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Handle the error
    die("Error fetching data");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Flow Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add this in the head section of your HTML document -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <canvas id="cashFlowChart" width="400" height="200"></canvas>

    <script>
        var ctx = document.getElementById('cashFlowChart').getContext('2d');

        var data = <?php echo json_encode($data); ?>;

        var dates = data.map(entry => entry.date_added);
        var prices = data.map(entry => entry.total_price);

        var colors = prices.map(price => price >= 0 ? 'blue' : 'gray');

        var chartData = {
            labels: dates,
            datasets: [{
                label: 'Cash Flow',
                data: prices,
                backgroundColor: colors,
            }]
        };

        var chartOptions = {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: chartOptions
        });
    </script>
</body>
</html>
